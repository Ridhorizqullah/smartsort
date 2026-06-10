<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WasteCategorySeeder extends Seeder
{
    /**
     * Seed 10 kategori sampah dengan harga per kg.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Kertas',          'price_per_kg' => 1500],
            ['name' => 'Kardus',          'price_per_kg' => 1200],
            ['name' => 'Plastik Botol',   'price_per_kg' => 2000],
            ['name' => 'Plastik Kresek',  'price_per_kg' => 800],
            ['name' => 'Besi & Logam',    'price_per_kg' => 3500],
            ['name' => 'Alumunium',       'price_per_kg' => 8000],
            ['name' => 'Kaca / Botol Kaca','price_per_kg' => 600],
            ['name' => 'Minyak Jelantah', 'price_per_kg' => 3000],
            ['name' => 'Kaleng',          'price_per_kg' => 2500],
            ['name' => 'Elektronik (e-waste)', 'price_per_kg' => 5000],
        ];

        foreach ($categories as $category) {
            DB::table('waste_categories')->insertOrIgnore([
                ...$category,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
