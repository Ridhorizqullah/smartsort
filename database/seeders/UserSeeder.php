<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed akun admin + petugas + 10 warga.
     */
    public function run(): void
    {
        // =============================================
        // AKUN ADMIN & PETUGAS
        // =============================================

        User::firstOrCreate(
            ['nik' => '3201010101010001'],
            [
                'name'       => 'Super Admin',
                'email'      => 'admin@smartsort.id',
                'password'   => Hash::make('admin123'),
                'role'       => 'admin',
                'phone'      => '081234567890',
                'address'    => 'Jl. Merdeka No. 1',
                'rt_rw'      => '00/00',
                'saldo_poin' => 0,
            ]
        );

        User::firstOrCreate(
            ['nik' => '3201010101010002'],
            [
                'name'       => 'Budi Petugas',
                'email'      => 'petugas@smartsort.id',
                'password'   => Hash::make('petugas123'),
                'role'       => 'petugas',
                'phone'      => '082345678901',
                'address'    => 'Jl. Pahlawan No. 5',
                'rt_rw'      => '01/01',
                'saldo_poin' => 0,
            ]
        );

        // =============================================
        // 10 AKUN WARGA
        // =============================================

        $wargas = [
            [
                'nik'        => '3201010101010101',
                'name'       => 'Siti Rahayu',
                'email'      => 'siti.rahayu@gmail.com',
                'phone'      => '08111111101',
                'rt_rw'      => 'RT 01 / RW 02',
                'address'    => 'Jl. Mawar No. 12',
                'saldo_poin' => 45000,
            ],
            [
                'nik'        => '3201010101010102',
                'name'       => 'Ahmad Fauzi',
                'email'      => 'ahmad.fauzi@gmail.com',
                'phone'      => '08111111102',
                'rt_rw'      => 'RT 02 / RW 01',
                'address'    => 'Jl. Melati No. 7',
                'saldo_poin' => 28000,
            ],
            [
                'nik'        => '3201010101010103',
                'name'       => 'Dewi Kartika',
                'email'      => 'dewi.kartika@yahoo.com',
                'phone'      => '08111111103',
                'rt_rw'      => 'RT 03 / RW 02',
                'address'    => 'Jl. Kenanga No. 3',
                'saldo_poin' => 62000,
            ],
            [
                'nik'        => '3201010101010104',
                'name'       => 'Hendra Wijaya',
                'email'      => 'hendra.wijaya@gmail.com',
                'phone'      => '08111111104',
                'rt_rw'      => 'RT 01 / RW 03',
                'address'    => 'Jl. Anggrek No. 21',
                'saldo_poin' => 15000,
            ],
            [
                'nik'        => '3201010101010105',
                'name'       => 'Rina Susanti',
                'email'      => 'rina.susanti@gmail.com',
                'phone'      => '08111111105',
                'rt_rw'      => 'RT 04 / RW 01',
                'address'    => 'Jl. Flamboyan No. 9',
                'saldo_poin' => 33500,
            ],
            [
                'nik'        => '3201010101010106',
                'name'       => 'Bambang Sutrisno',
                'email'      => 'bambang.s@gmail.com',
                'phone'      => '08111111106',
                'rt_rw'      => 'RT 02 / RW 03',
                'address'    => 'Jl. Dahlia No. 15',
                'saldo_poin' => 77500,
            ],
            [
                'nik'        => '3201010101010107',
                'name'       => 'Yuni Lestari',
                'email'      => 'yuni.lestari@gmail.com',
                'phone'      => '08111111107',
                'rt_rw'      => 'RT 03 / RW 03',
                'address'    => 'Jl. Bougenville No. 6',
                'saldo_poin' => 9500,
            ],
            [
                'nik'        => '3201010101010108',
                'name'       => 'Doni Prasetyo',
                'email'      => 'doni.prasetyo@gmail.com',
                'phone'      => '08111111108',
                'rt_rw'      => 'RT 01 / RW 04',
                'address'    => 'Jl. Teratai No. 18',
                'saldo_poin' => 51000,
            ],
            [
                'nik'        => '3201010101010109',
                'name'       => 'Sri Mulyani',
                'email'      => 'sri.mulyani@gmail.com',
                'phone'      => '08111111109',
                'rt_rw'      => 'RT 04 / RW 02',
                'address'    => 'Jl. Cempaka No. 4',
                'saldo_poin' => 22000,
            ],
            [
                'nik'        => '3201010101010110',
                'name'       => 'Rudi Hermawan',
                'email'      => 'rudi.hermawan@gmail.com',
                'phone'      => '08111111110',
                'rt_rw'      => 'RT 05 / RW 01',
                'address'    => 'Jl. Seruni No. 11',
                'saldo_poin' => 40000,
            ],
        ];

        foreach ($wargas as $warga) {
            User::firstOrCreate(
                ['nik' => $warga['nik']],
                [
                    ...$warga,
                    'password' => Hash::make('warga123'),
                    'role'     => 'warga',
                ]
            );
        }
    }
}
