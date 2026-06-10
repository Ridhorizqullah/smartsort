<?php

/*
|--------------------------------------------------------------------------
| SmartSort Global Helper Functions
|--------------------------------------------------------------------------
| Fungsi helper global yang tersedia di seluruh aplikasi (Controller, Blade, dll).
| File ini di-autoload melalui composer.json.
|
| Penggunaan: format_poin(1500)  →  "1.500 Poin"
*/

if (!function_exists('format_poin')) {
    /**
     * Format angka poin menjadi string yang mudah dibaca.
     *
     * @param  int|float $poin
     * @return string
     *
     * Contoh: format_poin(1500) → "1.500 Poin"
     */
    function format_poin(int|float $poin): string
    {
        return number_format($poin, 0, ',', '.') . ' Poin';
    }
}

if (!function_exists('format_tanggal')) {
    /**
     * Format tanggal ke dalam Bahasa Indonesia yang mudah dibaca.
     *
     * @param  string|\Carbon\Carbon $tanggal
     * @return string
     *
     * Contoh: format_tanggal('2026-06-10') → "Selasa, 10 Juni 2026"
     */
    function format_tanggal(string|\Carbon\Carbon $tanggal): string
    {
        $carbon = $tanggal instanceof \Carbon\Carbon
            ? $tanggal
            : \Carbon\Carbon::parse($tanggal);

        return $carbon->translatedFormat('l, d F Y');
    }
}

if (!function_exists('format_tanggal_singkat')) {
    /**
     * Format tanggal singkat.
     *
     * @param  string|\Carbon\Carbon $tanggal
     * @return string
     *
     * Contoh: format_tanggal_singkat('2026-06-10') → "10 Jun 2026"
     */
    function format_tanggal_singkat(string|\Carbon\Carbon $tanggal): string
    {
        $carbon = $tanggal instanceof \Carbon\Carbon
            ? $tanggal
            : \Carbon\Carbon::parse($tanggal);

        return $carbon->translatedFormat('d M Y');
    }
}

if (!function_exists('status_badge_class')) {
    /**
     * Kembalikan CSS class Tailwind untuk badge status penukaran.
     *
     * @param  string $status
     * @return string
     *
     * Contoh: status_badge_class('approved') → "bg-green-100 text-green-800"
     */
    function status_badge_class(string $status): string
    {
        return match ($status) {
            'pending'   => 'bg-yellow-100 text-yellow-800',
            'approved'  => 'bg-blue-100 text-blue-800',
            'ready'     => 'bg-purple-100 text-purple-800',
            'completed' => 'bg-green-100 text-green-800',
            'rejected'  => 'bg-red-100 text-red-800',
            default     => 'bg-gray-100 text-gray-800',
        };
    }
}

if (!function_exists('status_label')) {
    /**
     * Kembalikan label Bahasa Indonesia untuk status penukaran.
     *
     * @param  string $status
     * @return string
     */
    function status_label(string $status): string
    {
        return match ($status) {
            'pending'   => 'Menunggu',
            'approved'  => 'Disetujui',
            'ready'     => 'Siap Diambil',
            'completed' => 'Selesai',
            'rejected'  => 'Ditolak',
            default     => ucfirst($status),
        };
    }
}
