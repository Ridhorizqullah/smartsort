<?php

namespace App\Services;

use App\Models\User;
use App\Models\Reward;
use App\Models\Redemption;
use App\Models\RedemptionDetail;
use App\Models\PointLedger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class RedemptionService
{
    /**
     * STEP 1: Warga Request Penukaran Sembako
     */
    public function requestRedemption($userId, $items, $idempotencyKey)
    {
        // 1. Validasi Mandiri
        if (empty($items)) {
            throw new Exception("Daftar penukaran tidak boleh kosong.");
        }
        if (count($items) > 20) {
            throw new Exception("Batas maksimal 20 item per penukaran.");
        }

        // 2. Pre-check Idempotency
        if (Redemption::where('idempotency_key', $idempotencyKey)->exists()) {
            throw new Exception("Sistem menolak duplikasi: Penukaran ini sedang atau telah diproses.");
        }

        // 3. Agregasi Item & Validasi nilai
        $groupedItems = [];
        foreach ($items as $item) {
            if (!isset($item['qty']) || $item['qty'] <= 0) {
                throw new Exception("Jumlah penukaran harus lebih besar dari 0.");
            }
            $rId = $item['reward_id'];
            if (isset($groupedItems[$rId])) {
                $groupedItems[$rId]['qty'] += $item['qty'];
            } else {
                $groupedItems[$rId] = [
                    'reward_id' => $rId,
                    'qty' => $item['qty']
                ];
            }
        }
        $groupedItems = array_values($groupedItems);

        // 4. Fix N+1 Query: Preload semua reward
        $rewardIds = array_column($groupedItems, 'reward_id');
        $rewards = Reward::whereIn('id', $rewardIds)->get()->keyBy('id');

        try {
            return DB::transaction(function () use ($userId, $groupedItems, $rewards, $idempotencyKey) {
                // [FIX] Lock user DULU & cek saldo sebelum menyimpan apapun ke DB
                $user = User::where('id', $userId)->lockForUpdate()->firstOrFail();

                // Pre-hitung total poin yang dibutuhkan
                $totalPoint = 0;
                foreach ($groupedItems as $item) {
                    $reward = $rewards->get($item['reward_id']);
                    if (!$reward) {
                        throw new Exception("Reward dengan ID {$item['reward_id']} tidak ditemukan.");
                    }
                    $totalPoint += round($reward->point_cost * $item['qty'], 2);
                }
                $totalPoint = round($totalPoint, 2);

                // Validasi saldo sebelum insert apapun ke DB
                if ($user->saldo_poin < $totalPoint) {
                    throw new Exception("Sistem menolak: Saldo poin tidak mencukupi untuk penukaran ini.");
                }

                // Buat header redemption (status pending - saldo/stok belum dipotong)
                $redemption = Redemption::create([
                    'user_id' => $userId,
                    'total_point' => $totalPoint,
                    'status' => 'pending',
                    'idempotency_key' => $idempotencyKey,
                    'expires_at' => now()->addDays(2)
                ]);

                // Simpan detail item
                foreach ($groupedItems as $item) {
                    $reward = $rewards->get($item['reward_id']);
                    $itemPoint = round($reward->point_cost * $item['qty'], 2);
                    RedemptionDetail::create([
                        'redemption_id'  => $redemption->id,
                        'reward_id'      => $reward->id,
                        'qty'            => $item['qty'],
                        'point_snapshot' => $reward->point_cost,  // snapshot harga reward saat penukaran
                        'subtotal_point' => $itemPoint,
                    ]);
                }

                return $redemption;
            }, 3);
            
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                throw new Exception("Sistem menolak duplikasi: Permintaan ini sedang atau telah diproses.");
            }
            Log::error("Database Error on requestRedemption: " . $e->getMessage(), ['user_id' => $userId]);
            throw $e;
        } catch (\Exception $e) {
            Log::error("Error on requestRedemption: " . $e->getMessage(), ['user_id' => $userId]);
            throw $e;
        }
    }

    /**
     * STEP 2: Admin Approve (Validasi stok, potong saldo, catat ledger)
     */
    public function approveRedemption($redemptionId, $adminId, $tanggalAmbil)
    {
        try {
            return DB::transaction(function () use ($redemptionId, $adminId, $tanggalAmbil) {
                // 1. KONSISTENSI LOCKING URUTAN: Redemption -> User -> Reward
                $redemption = Redemption::where('id', $redemptionId)->lockForUpdate()->firstOrFail();
                
                // Validasi status
                if ($redemption->status !== 'pending') {
                    throw new Exception("Persetujuan gagal: Status tiket tidak valid (Bukan Pending).");
                }

                // Validasi kadaluarsa
                if (now()->greaterThan($redemption->expires_at)) {
                    // Update inside the same transaction directly instead of calling a separate non-transaction method
                    $redemption->update([
                        'admin_id' => $adminId,
                        'status' => 'rejected',
                        'rejected_at' => now(),
                        'catatan_admin' => "Batal Otomatis: Batas waktu penukaran habis (Expired)."
                    ]);
                    throw new Exception("Persetujuan gagal: Permintaan warga telah kedaluwarsa.");
                }
                
                // 2. Lock Saldo Warga
                $user = User::where('id', $redemption->user_id)->lockForUpdate()->firstOrFail();
                if ($user->saldo_poin < $redemption->total_point) {
                    throw new Exception("Persetujuan gagal: Saldo poin warga tidak mencukupi saat ini.");
                }

                // 3. Lock Stok Barang secara aman
                foreach ($redemption->details as $detail) {
                    $reward = Reward::where('id', $detail->reward_id)->lockForUpdate()->firstOrFail();
                    if ($reward->stock < $detail->qty) {
                        throw new Exception("Persetujuan gagal: Stok [{$reward->name}] sudah habis atau tidak mencukupi.");
                    }
                    
                    // Potong Stok
                    $reward->decrement('stock', $detail->qty);
                }

                // 4. Potong Saldo User (Saldo dipotong karena approval berhasil)
                $user->saldo_poin = round($user->saldo_poin - $redemption->total_point, 2);
                $user->save();

                // 5. Catat Ledger Point (Debit)
                PointLedger::create([
                    'user_id' => $user->id,
                    'type' => 'debit',
                    'amount' => $redemption->total_point,
                    'redemption_id' => $redemption->id
                ]);

                // 6. Update Status Header ke Approved
                $redemption->update([
                    'admin_id' => $adminId,
                    'status' => 'approved',
                    'approved_at' => now(),
                    'tanggal_ambil' => $tanggalAmbil
                ]);

                return $redemption;
            }, 3);
        } catch (\Exception $e) {
            Log::error("Error on approveRedemption: " . $e->getMessage(), ['redemption_id' => $redemptionId, 'admin_id' => $adminId]);
            throw $e;
        }
    }

    /**
     * STEP 3: Admin Reject (Penolakan tanpa potongan)
     */
    public function rejectRedemption($redemptionId, $adminId, $catatan)
    {
        try {
            return DB::transaction(function () use ($redemptionId, $adminId, $catatan) {
                $redemption = Redemption::where('id', $redemptionId)->lockForUpdate()->firstOrFail();
                if ($redemption->status !== 'pending') {
                    throw new Exception("Penolakan gagal: Status bukan pending.");
                }

                $redemption->update([
                    'admin_id' => $adminId,
                    'status' => 'rejected',
                    'rejected_at' => now(),
                    'catatan_admin' => $catatan
                ]);
                return $redemption;
            });
        } catch (\Exception $e) {
            Log::error("Error on rejectRedemption: " . $e->getMessage(), ['redemption_id' => $redemptionId]);
            throw $e;
        }
    }

    /**
     * STEP 4: Barang Siap (Tandai paket sembako sudah dikemas di loket)
     */
    public function markAsReady($redemptionId)
    {
        try {
            return DB::transaction(function () use ($redemptionId) {
                $redemption = Redemption::where('id', $redemptionId)->where('status', 'approved')->lockForUpdate()->firstOrFail();
                $redemption->update([
                    'status' => 'ready', 
                    'ready_at' => now()
                ]);
                return $redemption;
            });
        } catch (\Exception $e) {
            Log::error("Error on markAsReady: " . $e->getMessage(), ['redemption_id' => $redemptionId]);
            throw $e;
        }
    }

    /**
     * STEP 5: Selesai (Barang telah fisik diserahkan ke Warga)
     */
    public function markAsCompleted($redemptionId)
    {
        try {
            return DB::transaction(function () use ($redemptionId) {
                $redemption = Redemption::where('id', $redemptionId)->where('status', 'ready')->lockForUpdate()->firstOrFail();
                $redemption->update([
                    'status' => 'completed', 
                    'completed_at' => now()
                ]);
                return $redemption;
            });
        } catch (\Exception $e) {
            Log::error("Error on markAsCompleted: " . $e->getMessage(), ['redemption_id' => $redemptionId]);
            throw $e;
        }
    }
}
