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
        // Berat item telah disesuaikan agar total poin mencakup saldo awal lama + poin transaksi asli
        $transaksiData = [
            [
                'warga'  => 'Siti Rahayu',
                'items'  => [['Kertas', 5.0], ['Kardus', 3.5], ['Elektronik (e-waste)', 9.0]],
                'date'   => now()->subDays(30),
            ],
            [
                'warga'  => 'Ahmad Fauzi',
                'items'  => [['Plastik Botol', 18.0], ['Plastik Kresek', 2.0]],
                'date'   => now()->subDays(27),
            ],
            [
                'warga'  => 'Dewi Kartika',
                'items'  => [['Besi & Logam', 6.5], ['Kaleng', 24.8]],
                'date'   => now()->subDays(25),
            ],
            [
                'warga'  => 'Hendra Wijaya',
                'items'  => [['Kertas', 3.0], ['Alumunium', 1.5], ['Minyak Jelantah', 5.0]],
                'date'   => now()->subDays(22),
            ],
            [
                'warga'  => 'Rina Susanti',
                'items'  => [['Kardus', 8.0], ['Kaca / Botol Kaca', 4.0], ['Kaleng', 13.4]],
                'date'   => now()->subDays(20),
            ],
            [
                'warga'  => 'Bambang Sutrisno',
                'items'  => [['Alumunium', 3.0], ['Besi & Logam', 5.0], ['Kaleng', 33.5]],
                'date'   => now()->subDays(17),
            ],
            [
                'warga'  => 'Yuni Lestari',
                'items'  => [['Minyak Jelantah', 5.0], ['Kaleng', 3.8]],
                'date'   => now()->subDays(14),
            ],
            [
                'warga'  => 'Doni Prasetyo',
                'items'  => [['Plastik Botol', 7.0], ['Kertas', 4.5], ['Minyak Jelantah', 17.0]],
                'date'   => now()->subDays(10),
            ],
            [
                'warga'  => 'Sri Mulyani',
                'items'  => [['Elektronik (e-waste)', 2.0], ['Besi & Logam', 3.0], ['Plastik Botol', 11.0]],
                'date'   => now()->subDays(6),
            ],
            [
                'warga'  => 'Rudi Hermawan',
                'items'  => [['Kardus', 5.0], ['Kertas', 6.0], ['Plastik Kresek', 3.0], ['Elektronik (e-waste)', 8.0]],
                'date'   => now()->subDays(2),
            ],
        ];

        $transactionService = app(\App\Services\TransactionService::class);

        foreach ($transaksiData as $data) {
            $warga = $wargas->get($data['warga']);
            if (!$warga) continue;

            $items = [];
            foreach ($data['items'] as [$categoryName, $weight]) {
                $category = $categories->get($categoryName);
                if (!$category) continue;

                $items[] = [
                    'waste_category_id' => $category->id,
                    'weight'            => $weight,
                ];
            }

            // Gunakan TransactionService untuk membuat transaksi (otomatis update saldo dan ledger)
            $transaction = $transactionService->createTransaction(
                $admin->id,
                $warga->id,
                $items,
                Str::uuid()->toString()
            );

            // Update timestamp agar sesuai riwayat historis
            $transaction->created_at = $data['date'];
            $transaction->updated_at = $data['date'];
            $transaction->save(['timestamps' => false]);

            TransactionDetail::where('transaction_id', $transaction->id)->update([
                'created_at' => $data['date'],
                'updated_at' => $data['date'],
            ]);

            PointLedger::where('transaction_id', $transaction->id)->update([
                'created_at' => $data['date'],
                'updated_at' => $data['date'],
            ]);
        }
    }
}
