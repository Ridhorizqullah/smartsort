<?php

namespace App\Services;

use App\Models\User;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\WasteCategory;
use App\Models\PointLedger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class TransactionService
{
    /**
     * Setor sampah dan dapatkan poin.
     */
    public function createTransaction($adminId, $userId, $items, $idempotencyKey)
    {
        // 1. Validasi Mandiri di Service Layer
        if (empty($items)) {
            throw new Exception("Daftar item tidak boleh kosong.");
        }
        if (count($items) > 20) {
            throw new Exception("Batas maksimal 20 item per transaksi.");
        }

        // 2. Pre-check Idempotency (Hindari hit DB berat jika duplicate)
        if (Transaction::where('idempotency_key', $idempotencyKey)->exists()) {
            throw new Exception("Sistem menolak duplikasi: Transaksi ini sedang atau telah diproses.");
        }

        // 3. Agregasi Item (Grouping duplicate categories) & Validasi nilai
        $groupedItems = [];
        foreach ($items as $item) {
            if (!isset($item['weight']) || $item['weight'] <= 0) {
                throw new Exception("Berat item harus lebih besar dari 0.");
            }
            $catId = $item['waste_category_id'];
            if (isset($groupedItems[$catId])) {
                $groupedItems[$catId]['weight'] += $item['weight'];
            } else {
                $groupedItems[$catId] = [
                    'waste_category_id' => $catId,
                    'weight' => $item['weight']
                ];
            }
        }
        $groupedItems = array_values($groupedItems);

        // 4. Fix N+1 Query: Preload semua kategori yang dibutuhkan
        $categoryIds = array_column($groupedItems, 'waste_category_id');
        $categories = WasteCategory::whereIn('id', $categoryIds)->get()->keyBy('id');

        try {
            // Mengulang eksekusi maksimal 3x jika terjadi deadlock di InnoDB
            return DB::transaction(function () use ($adminId, $userId, $groupedItems, $categories, $idempotencyKey) {
                
                // 1. Pessimistic Lock untuk mengamankan data pengguna dari transaksi paralel
                $user = User::where('id', $userId)->lockForUpdate()->firstOrFail();
                
                // 2. Buat header transaksi, kolom idempotency_key akan melempar 
                // QueryException(1062) jika ada double submit race condition
                $transaction = Transaction::create([
                    'user_id' => $user->id,
                    'admin_id' => $adminId,
                    'total_point' => 0,
                    'idempotency_key' => $idempotencyKey
                ]);

                $totalPoint = 0;

                // 3. Simpan rincian timbangan dan kalkulasi otomatis
                foreach ($groupedItems as $item) {
                    $category = $categories->get($item['waste_category_id']);
                    if (!$category) {
                        throw new Exception("Kategori sampah dengan ID {$item['waste_category_id']} tidak ditemukan.");
                    }

                    // [CRITICAL] Float Precision using round()
                    $point = round($item['weight'] * $category->price_per_kg, 2);
                    $totalPoint += $point;

                    TransactionDetail::create([
                        'transaction_id'    => $transaction->id,
                        'waste_category_id' => $category->id,
                        'weight'            => $item['weight'],
                        'price_snapshot'    => $category->price_per_kg, // snapshot harga saat transaksi
                        'subtotal_point'    => $point,
                    ]);
                }

                // 4. Update Header Transaksi (menggunakan precision juga untuk amannya)
                $totalPoint = round($totalPoint, 2);
                $transaction->update(['total_point' => $totalPoint]);

                // 5. Update Saldo User secara aman (karena sudah di-lock sebelumnya)
                $user->saldo_poin += $totalPoint;
                $user->save();

                // 6. Catat Point Ledger untuk audit trail
                PointLedger::create([
                    'user_id' => $user->id,
                    'type' => 'credit',
                    'amount' => $totalPoint,
                    'transaction_id' => $transaction->id
                ]);

                return $transaction;
            }, 3); 

        } catch (\Illuminate\Database\QueryException $e) {
            // MySQL Error Code 1062: Duplicate entry
            if ($e->errorInfo[1] == 1062) {
                throw new Exception("Sistem menolak duplikasi: Transaksi ini sedang atau telah diproses.");
            }
            Log::error("Database Error on createTransaction: " . $e->getMessage(), [
                'user_id' => $userId,
                'admin_id' => $adminId
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error("Error on createTransaction: " . $e->getMessage(), [
                'user_id' => $userId,
                'admin_id' => $adminId
            ]);
            throw $e;
        }
    }
}
