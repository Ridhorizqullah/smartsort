<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat akun admin pertama
        User::firstOrCreate(
            ['nik' => '123456'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@smartsord.id',
                'password' => Hash::make('admin'),
                'role' => 'admin',
                'phone' => '081234567890',
                'address' => 'Kantor Pusat',
                'rt_rw' => '00/00',
            ]
        );
    }
}
