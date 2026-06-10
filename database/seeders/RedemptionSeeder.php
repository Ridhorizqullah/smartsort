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

        foreach ($redemptionData as $data) {
            $warga = $wargas->get($data['warga']);
            if (!$warga) continue;

            // Hitung total poin
            $totalPoint = 0;
            $details    = [];

            foreach ($data['items'] as [$rewardName, $qty]) {
                $reward = $rewards->get($rewardName);
                if (!$reward) continue;

                $subtotal    = round($reward->point_cost * $qty, 2);
                $totalPoint += $subtotal;
                $details[]   = [
                    'reward'         => $reward,
                    'qty'            => $qty,
                    'point_snapshot' => $reward->point_cost,
                    'subtotal_point' => $subtotal,
                ];
            }

            $totalPoint = round($totalPoint, 2);

            // Buat header redemption
            $redemption = Redemption::create([
                'user_id'         => $warga->id,
                'admin_id'        => in_array($data['status'], ['approved', 'ready', 'completed', 'rejected']) ? $admin->id : null,
                'total_point'     => $totalPoint,
                'status'          => $data['status'],
                'tanggal_ambil'   => $data['tanggal_ambil'] ?? null,
                'catatan_admin'   => $data['catatan_admin'] ?? null,
                'idempotency_key' => Str::uuid()->toString(),
                'approved_at'     => in_array($data['status'], ['approved', 'ready', 'completed']) ? $data['date']->copy()->addDays(1) : null,
                'ready_at'        => in_array($data['status'], ['ready', 'completed']) ? $data['date']->copy()->addDays(2) : null,
                'completed_at'    => $data['completed_at'] ?? null,
                'rejected_at'     => $data['status'] === 'rejected' ? $data['date']->copy()->addDays(1) : null,
                'expires_at'      => $data['date']->copy()->addDays(2),
                'created_at'      => $data['date'],
                'updated_at'      => $data['date'],
            ]);

            // Buat detail redemption
            foreach ($details as $detail) {
                RedemptionDetail::create([
                    'redemption_id'  => $redemption->id,
                    'reward_id'      => $detail['reward']->id,
                    'qty'            => $detail['qty'],
                    'point_snapshot' => $detail['point_snapshot'],
                    'subtotal_point' => $detail['subtotal_point'],
                    'created_at'     => $data['date'],
                    'updated_at'     => $data['date'],
                ]);
            }

            // Catat debit di PointLedger hanya jika sudah approved/completed/ready
            if (in_array($data['status'], ['approved', 'ready', 'completed'])) {
                PointLedger::create([
                    'user_id'       => $warga->id,
                    'type'          => 'debit',
                    'amount'        => $totalPoint,
                    'redemption_id' => $redemption->id,
                    'description'   => 'Penukaran poin ke sembako #' . $redemption->id,
                    'created_at'    => $data['date'],
                    'updated_at'    => $data['date'],
                ]);
            }
        }
    }
}
