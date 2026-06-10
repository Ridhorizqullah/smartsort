@extends('layouts.admin')

@section('title', 'Admin Dashboard - SmartSort')
@section('header', 'Dashboard Utama')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/30 flex flex-col justify-between hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-on-surface-variant text-xs font-bold uppercase tracking-wider">Total Warga</h3>
            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                <span class="material-symbols-outlined">group</span>
            </div>
        </div>
        <div>
            <p class="text-3xl font-extrabold text-on-surface tracking-tight">{{ number_format($totalWarga) }}</p>
            <span class="text-[10px] font-semibold text-on-surface-variant">Terdaftar aktif</span>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/30 flex flex-col justify-between hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-on-surface-variant text-xs font-bold uppercase tracking-wider">Total Transaksi</h3>
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                <span class="material-symbols-outlined">local_shipping</span>
            </div>
        </div>
        <div>
            <p class="text-3xl font-extrabold text-on-surface tracking-tight">{{ number_format($totalTransaksi) }}</p>
            <span class="text-[10px] font-semibold text-on-surface-variant">Setoran timbangan</span>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/30 flex flex-col justify-between hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-on-surface-variant text-xs font-bold uppercase tracking-wider">Poin Beredar</h3>
            <div class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center text-yellow-600">
                <span class="material-symbols-outlined">eco</span>
            </div>
        </div>
        <div>
            <p class="text-3xl font-extrabold text-on-surface tracking-tight">{{ number_format($totalPoinBeredar) }}</p>
            <span class="text-[10px] font-semibold text-on-surface-variant">Saldo poin warga</span>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/30 flex flex-col justify-between hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-on-surface-variant text-xs font-bold uppercase tracking-wider">Antrean Penukaran</h3>
            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-error">
                <span class="material-symbols-outlined">fact_check</span>
            </div>
        </div>
        <div>
            <p class="text-3xl font-extrabold text-on-surface tracking-tight">{{ number_format($pendingPenukaran) }}</p>
            <span class="text-[10px] font-semibold text-error">Butuh persetujuan</span>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
    <div class="px-6 py-5 border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-lowest/50">
        <div>
            <h2 class="text-lg font-bold text-on-surface">Transaksi Timbangan Terbaru</h2>
            <p class="text-xs text-on-surface-variant mt-0.5">Daftar setoran sampah warga yang baru saja direkap.</p>
        </div>
        <a href="{{ route('admin.transaksi') }}" class="text-xs font-bold text-primary hover:text-primary-container flex items-center gap-1 py-2 px-3 bg-primary/5 rounded-lg border border-primary/10 transition-colors">
            <span class="material-symbols-outlined text-sm">add</span>
            Buka POS Timbangan
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-surface-container-low text-on-surface-variant font-bold border-b border-outline-variant/30">
                <tr>
                    <th class="px-6 py-4">ID TRX</th>
                    <th class="px-6 py-4">Nama Warga</th>
                    <th class="px-6 py-4">Tanggal Rekap</th>
                    <th class="px-6 py-4 text-right">Poin Masuk</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/20">
                @forelse($recentTransactions as $trx)
                <tr class="hover:bg-surface-container-lowest transition-colors">
                    <td class="px-6 py-4 font-mono text-xs font-bold text-on-surface-variant">#{{ substr($trx->idempotency_key ?? $trx->id, 0, 8) }}</td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-on-surface">{{ $trx->user->name ?? 'Warga Tidak Ditemukan' }}</div>
                        <div class="text-[10px] text-on-surface-variant mt-0.5">NIK: {{ $trx->user->nik ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 text-on-surface-variant">
                        <div class="flex items-center gap-1 text-xs font-semibold">
                            <span class="material-symbols-outlined text-xs">calendar_today</span>
                            {{ $trx->created_at->format('d M Y, H:i') }} WIB
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right font-extrabold text-primary">+{{ number_format($trx->total_point) }} Poin</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-on-surface-variant font-medium">
                        <span class="material-symbols-outlined text-5xl text-on-surface-variant/40 mb-3">inbox</span>
                        <p class="text-sm">Belum ada transaksi rekap timbangan tercatat.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
