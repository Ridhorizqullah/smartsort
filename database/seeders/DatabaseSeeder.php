<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Urutan seeding penting — jangan diubah!
     * Tabel yang memiliki foreign key harus di-seed SETELAH tabel referensinya.
     *
     *  1. WasteCategorySeeder  → tidak ada FK
     *  2. RewardSeeder         → tidak ada FK
     *  3. UserSeeder           → tidak ada FK
     *  4. TransactionSeeder    → FK ke users & waste_categories
     *  5. RedemptionSeeder     → FK ke users & rewards
     */
    public function run(): void
    {
        $this->call([
            WasteCategorySeeder::class,
            RewardSeeder::class,
            UserSeeder::class,
            TransactionSeeder::class,
            RedemptionSeeder::class,
        ]);
    }
}
