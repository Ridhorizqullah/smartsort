<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RewardSeeder extends Seeder
{
    /**
     * Seed 10 item sembako/reward yang bisa ditukar dengan poin.
     */
    public function run(): void
    {
        $rewards = [
            ['name' => 'Beras 5kg',            'point_cost' => 25000, 'stock' => 10],
            ['name' => 'Minyak Goreng 1L',     'point_cost' => 15000, 'stock' => 10],
            ['name' => 'Gula Pasir 1kg',       'point_cost' => 12000, 'stock' => 10],
            ['name' => 'Tepung Terigu 1kg',    'point_cost' => 8000,  'stock' => 10],
            ['name' => 'Mie Instan (5 bungkus)','point_cost' => 5000, 'stock' => 10],
            ['name' => 'Sabun Cuci Piring',    'point_cost' => 6000,  'stock' => 10],
            ['name' => 'Deterjen 1kg',         'point_cost' => 10000, 'stock' => 10],
            ['name' => 'Kecap Manis 135ml',    'point_cost' => 7000,  'stock' => 10],
            ['name' => 'Sarden Kaleng',        'point_cost' => 9000,  'stock' => 10],
            ['name' => 'Teh Celup (25 kantong)','point_cost' => 4000, 'stock' => 10],
        ];

        foreach ($rewards as $reward) {
            DB::table('rewards')->insertOrIgnore([
                ...$reward,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
