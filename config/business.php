<?php

/*
|--------------------------------------------------------------------------
| Business Configuration — SmartSort ERP
|--------------------------------------------------------------------------
| Konstanta bisnis yang dipakai di seluruh aplikasi.
| Akses via: config('business.key')
|
| Contoh: config('business.max_items_per_transaction')
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Limit Transaksi
    |--------------------------------------------------------------------------
    */

    // Batas maksimum item sampah yang bisa disetor dalam satu transaksi
    'max_items_per_transaction' => 20,

    // Batas maksimum berat per item (dalam Kg)
    'max_weight_per_item' => 9999,

    // Batas maksimum qty item penukaran per transaksi
    'max_qty_per_item' => 99,

    /*
    |--------------------------------------------------------------------------
    | Waktu Kedaluwarsa Penukaran
    |--------------------------------------------------------------------------
    */

    // Berapa hari penukaran berlaku sejak diajukan
    'redemption_expires_days' => 2,

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    // Jumlah item per halaman (dipakai di seluruh sistem)
    'pagination_size' => 10,

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting (informasional — diterapkan di routes)
    |--------------------------------------------------------------------------
    */

    // Catatan: throttle diterapkan langsung di routes/web.php & warga.php
    // Login:    throttle:5,1   (5 percobaan per menit)
    // Register: throttle:3,1   (3 registrasi per menit)
    // Redemption: throttle:10,1 (10 pengajuan per menit)

];
