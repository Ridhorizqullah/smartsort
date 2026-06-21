<?php

namespace Database\Seeders;

use App\Models\PointLedger;
use App\Models\Redemption;
use App\Models\RedemptionDetail;
use App\Models\Reward;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RedemptionSeeder extends Seeder
{
    /**
     * Seed 10 transaksi penukaran poin beserta detailnya.
     * Menampilkan berbagai status: pending, approved, completed, rejected.
     */
    public function run(): void
    {
        $admin   = User::where('role', 'admin')->first();
        $wargas  = User::where('role', 'warga')->get()->keyBy('name');
        $rewards = Reward::all()->keyBy('name');

        if (!$admin || $wargas->isEmpty() || $rewards->isEmpty()) {
            $this->command->warn('⚠ UserSeeder atau RewardSeeder belum dijalankan. Skip RedemptionSeeder.');
            return;
        }

        // 10 data penukaran dengan berbagai status
        $redemptionData = [
            [
                'warga'         => 'Siti Rahayu',
                'items'         => [['Beras 5kg', 1]],
                'status'        => 'completed',
                'tanggal_ambil' => now()->subDays(20)->toDateString(),
                'completed_at'  => now()->subDays(18),
                'date'          => now()->subDays(25),
            ],
            [
                'warga'         => 'Ahmad Fauzi',
                'items'         => [['Minyak Goreng 1L', 1], ['Gula Pasir 1kg', 1]],
                'status'        => 'completed',
                'tanggal_ambil' => now()->subDays(15)->toDateString(),
                'completed_at'  => now()->subDays(13),
                'date'          => now()->subDays(20),
            ],
            [
                'warga'         => 'Dewi Kartika',
                'items'         => [['Beras 5kg', 2]],
                'status'        => 'approved',
                'tanggal_ambil' => now()->addDays(2)->toDateString(),
                'completed_at'  => null,
                'date'          => now()->subDays(10),
            ],
            [
                'warga'         => 'Hendra Wijaya',
                'items'         => [['Mie Instan (5 bungkus)', 2]],
                'status'        => 'pending',
                'tanggal_ambil' => null,
                'completed_at'  => null,
                'date'          => now()->subDays(3),
            ],
            [
                'warga'         => 'Rina Susanti',
                'items'         => [['Deterjen 1kg', 1], ['Sabun Cuci Piring', 1]],
                'status'        => 'completed',
                'tanggal_ambil' => now()->subDays(8)->toDateString(),
                'completed_at'  => now()->subDays(7),
                'date'          => now()->subDays(14),
            ],
            [
                'warga'         => 'Bambang Sutrisno',
                'items'         => [['Beras 5kg', 2], ['Minyak Goreng 1L', 1]],
                'status'        => 'ready',
                'tanggal_ambil' => now()->addDays(1)->toDateString(),
                'completed_at'  => null,
                'date'          => now()->subDays(5),
            ],
            [
                'warga'         => 'Yuni Lestari',
                'items'         => [['Teh Celup (25 kantong)', 2]],
                'status'        => 'rejected',
                'tanggal_ambil' => null,
                'completed_at'  => null,
                'catatan_admin' => 'Saldo poin tidak mencukupi saat verifikasi.',
                'date'          => now()->subDays(12),
            ],
            [
                'warga'         => 'Doni Prasetyo',
                'items'         => [['Kecap Manis 135ml', 2], ['Sarden Kaleng', 1]],
                'status'        => 'approved',
                'tanggal_ambil' => now()->addDays(3)->toDateString(),
                'completed_at'  => null,
                'date'          => now()->subDays(4),
            ],
            [
                'warga'         => 'Sri Mulyani',
                'items'         => [['Tepung Terigu 1kg', 2]],
                'status'        => 'pending',
                'tanggal_ambil' => null,
                'completed_at'  => null,
                'date'          => now()->subDays(1),
            ],
            [
                'warga'         => 'Rudi Hermawan',
                'items'         => [['Beras 5kg', 1], ['Gula Pasir 1kg', 1]],
                'status'        => 'completed',
                'tanggal_ambil' => now()->subDays(5)->toDateString(),
                'completed_at'  => now()->subDays(4),
                'date'          => now()->subDays(9),
            ],
        ];

        $redemptionService = app(\App\Services\RedemptionService::class);

        foreach ($redemptionData as $data) {
            $warga = $wargas->get($data['warga']);
            if (!$warga) continue;

            $items = [];
            foreach ($data['items'] as [$rewardName, $qty]) {
                $reward = $rewards->get($rewardName);
                if (!$reward) continue;

                $items[] = [
                    'reward_id' => $reward->id,
                    'qty'       => $qty,
                ];
            }

            // 1. Request redemption (creates pending ticket)
            $redemption = $redemptionService->requestRedemption(
                $warga->id,
                $items,
                Str::uuid()->toString()
            );

            // 2. Transition through the statuses using the service methods
            if (in_array($data['status'], ['approved', 'ready', 'completed'])) {
                $tanggalAmbil = $data['tanggal_ambil'] ?? now()->addDays(2)->toDateString();
                $redemptionService->approveRedemption($redemption->id, $admin->id, $tanggalAmbil);
            }

            if (in_array($data['status'], ['ready', 'completed'])) {
                $redemptionService->markAsReady($redemption->id);
            }

            if ($data['status'] === 'completed') {
                $redemptionService->markAsCompleted($redemption->id);
            }

            if ($data['status'] === 'rejected') {
                $catatan = $data['catatan_admin'] ?? 'Permohonan ditolak oleh admin.';
                $redemptionService->rejectRedemption($redemption->id, $admin->id, $catatan);
            }

            // 3. Update timestamps of all created models to match history
            $redemption->refresh();
            
            $redemption->created_at = $data['date'];
            $redemption->updated_at = $data['date'];
            
            if ($redemption->approved_at) {
                $redemption->approved_at = $data['date']->copy()->addDays(1);
            }
            if ($redemption->ready_at) {
                $redemption->ready_at = $data['date']->copy()->addDays(2);
            }
            if ($redemption->completed_at) {
                $redemption->completed_at = $data['completed_at'];
            }
            if ($redemption->rejected_at) {
                $redemption->rejected_at = $data['date']->copy()->addDays(1);
            }
            
            $redemption->expires_at = $data['date']->copy()->addDays(2);
            $redemption->save(['timestamps' => false]);

            RedemptionDetail::where('redemption_id', $redemption->id)->update([
                'created_at' => $data['date'],
                'updated_at' => $data['date'],
            ]);

            PointLedger::where('redemption_id', $redemption->id)->update([
                'created_at' => $data['date'],
                'updated_at' => $data['date'],
            ]);
        }
    }
}
