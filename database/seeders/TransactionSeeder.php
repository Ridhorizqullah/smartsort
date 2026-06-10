<?php

namespace Database\Seeders;

use App\Models\PointLedger;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Models\WasteCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    /**
     * Seed 10 transaksi setoran sampah beserta detailnya.
     * Setiap transaksi dilakukan oleh warga berbeda, diproses admin.
     */
    public function run(): void
    {
        $admin      = User::where('role', 'admin')->first();
        $wargas     = User::where('role', 'warga')->get()->keyBy('name');
        $categories = WasteCategory::all()->keyBy('name');

        if (!$admin || $wargas->isEmpty() || $categories->isEmpty()) {
            $this->command->warn('⚠ UserSeeder atau WasteCategorySeeder belum dijalankan. Skip TransactionSeeder.');
            return;
        }

        // 10 data transaksi: setiap entri berisi [nama warga, [kategori => berat_kg]]
        $transaksiData = [
            [
                'warga'  => 'Siti Rahayu',
                'items'  => [['Kertas', 5.0], ['Kardus', 3.5]],
                'date'   => now()->subDays(30),
            ],
            [
                'warga'  => 'Ahmad Fauzi',
                'items'  => [['Plastik Botol', 4.0], ['Plastik Kresek', 2.0]],
                'date'   => now()->subDays(27),
            ],
            [
                'warga'  => 'Dewi Kartika',
                'items'  => [['Besi & Logam', 6.5]],
                'date'   => now()->subDays(25),
            ],
            [
                'warga'  => 'Hendra Wijaya',
                'items'  => [['Kertas', 3.0], ['Alumunium', 1.5]],
                'date'   => now()->subDays(22),
            ],
            [
                'warga'  => 'Rina Susanti',
                'items'  => [['Kardus', 8.0], ['Kaca / Botol Kaca', 4.0]],
                'date'   => now()->subDays(20),
            ],
            [
                'warga'  => 'Bambang Sutrisno',
                'items'  => [['Alumunium', 3.0], ['Besi & Logam', 5.0], ['Kaleng', 2.5]],
                'date'   => now()->subDays(17),
            ],
            [
                'warga'  => 'Yuni Lestari',
                'items'  => [['Minyak Jelantah', 5.0]],
                'date'   => now()->subDays(14),
            ],
            [
                'warga'  => 'Doni Prasetyo',
                'items'  => [['Plastik Botol', 7.0], ['Kertas', 4.5]],
                'date'   => now()->subDays(10),
            ],
            [
                'warga'  => 'Sri Mulyani',
                'items'  => [['Elektronik (e-waste)', 2.0], ['Besi & Logam', 3.0]],
                'date'   => now()->subDays(6),
            ],
            [
                'warga'  => 'Rudi Hermawan',
                'items'  => [['Kardus', 5.0], ['Kertas', 6.0], ['Plastik Kresek', 3.0]],
                'date'   => now()->subDays(2),
            ],
        ];

        foreach ($transaksiData as $data) {
            $warga = $wargas->get($data['warga']);
            if (!$warga) continue;

            // Hitung total poin
            $totalPoint = 0;
            $details    = [];

            foreach ($data['items'] as [$categoryName, $weight]) {
                $category = $categories->get($categoryName);
                if (!$category) continue;

                $subtotal       = round($weight * $category->price_per_kg, 2);
                $totalPoint    += $subtotal;
                $details[]      = [
                    'category'       => $category,
                    'weight'         => $weight,
                    'price_snapshot' => $category->price_per_kg,
                    'subtotal_point' => $subtotal,
                ];
            }

            $totalPoint = round($totalPoint, 2);

            // Buat header transaksi
            $transaction = Transaction::create([
                'user_id'         => $warga->id,
                'admin_id'        => $admin->id,
                'total_point'     => $totalPoint,
                'idempotency_key' => Str::uuid()->toString(),
                'created_at'      => $data['date'],
                'updated_at'      => $data['date'],
            ]);

            // Buat detail transaksi
            foreach ($details as $detail) {
                TransactionDetail::create([
                    'transaction_id'    => $transaction->id,
                    'waste_category_id' => $detail['category']->id,
                    'weight'            => $detail['weight'],
                    'price_snapshot'    => $detail['price_snapshot'],
                    'subtotal_point'    => $detail['subtotal_point'],
                    'created_at'        => $data['date'],
                    'updated_at'        => $data['date'],
                ]);
            }

            // Catat di PointLedger (audit trail)
            PointLedger::create([
                'user_id'        => $warga->id,
                'type'           => 'credit',
                'amount'         => $totalPoint,
                'transaction_id' => $transaction->id,
                'description'    => 'Poin dari setoran sampah #' . $transaction->id,
                'created_at'     => $data['date'],
                'updated_at'     => $data['date'],
            ]);
        }
    }
}
