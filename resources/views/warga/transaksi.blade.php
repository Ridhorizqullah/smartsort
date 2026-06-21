@extends('layouts.app')

@section('title', 'Riwayat Setor Sampah - SmartSort')

@section('content')
<section class="mb-stack-lg animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col gap-1 mb-stack-md">
        <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">Riwayat Setoran Sampah</h1>
        <p class="font-body-md text-sm text-tertiary">Pantau aktivitas setoran dan perolehan poin Anda.</p>
    </div>

    <!-- Statistik Compact (Inline Bar) -->
    <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-xs text-on-surface-variant font-medium bg-surface-container-lowest px-4 py-2.5 rounded-xl border border-outline-variant/20 shadow-sm mb-6">
        <span class="flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px] text-emerald-600 font-bold">package_2</span>
            <span>Total Setoran: <strong class="text-on-surface font-extrabold">{{ number_format($totalSetoran) }}</strong></span>
        </span>
        <span class="text-outline-variant/40">•</span>
        <span class="flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px] text-primary font-bold">scale</span>
            <span>Total Berat: <strong class="text-on-surface font-extrabold">{{ number_format($totalBerat, 1, ',', '.') }} kg</strong></span>
        </span>
        <span class="text-outline-variant/40">•</span>
        <span class="flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px] text-secondary font-bold">eco</span>
            <span>Total Poin: <strong class="text-primary font-black">+{{ number_format($totalPoin, 0, ',', '.') }} Poin</strong></span>
        </span>
    </div>

    <!-- Filter Bar (Compact) -->
    <form action="{{ route('warga.transaksi') }}" method="GET" class="flex flex-col sm:flex-row gap-3 mb-6">
        <!-- Search Input -->
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-on-surface-variant/50">
                <span class="material-symbols-outlined text-[18px]">search</span>
            </span>
            <input type="text" 
                name="search" 
                id="transaksi-search-input"
                value="{{ request('search') }}"
                placeholder="Cari setoran..." 
                class="w-full pl-9 pr-4 py-2 rounded-xl border border-outline-variant/35 focus:border-primary focus:ring-2 focus:ring-primary/20 bg-surface-container-lowest text-xs transition-all focus:outline-none">
        </div>

        <!-- Dropdown Waktu -->
        <div class="w-full sm:w-48">
            <select name="waktu" 
                id="transaksi-waktu-select"
                onchange="this.form.submit()" 
                class="w-full px-3 py-2 rounded-xl border border-outline-variant/35 focus:border-primary focus:ring-2 focus:ring-primary/20 bg-surface-container-lowest text-xs transition-all cursor-pointer focus:outline-none">
                <option value="">Semua Waktu</option>
                <option value="hari_ini" {{ request('waktu') == 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                <option value="minggu_ini" {{ request('waktu') == 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                <option value="bulan_ini" {{ request('waktu') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                <option value="bulan_lalu" {{ request('waktu') == 'bulan_lalu' ? 'selected' : '' }}>Bulan Lalu</option>
                <option value="tahun_ini" {{ request('waktu') == 'tahun_ini' ? 'selected' : '' }}>Tahun Ini</option>
            </select>
        </div>
        
        @if(request()->anyFilled(['search', 'waktu']))
            <a href="{{ route('warga.transaksi') }}" class="px-3.5 py-2 bg-surface-container-low hover:bg-surface-container text-on-surface-variant text-xs font-bold rounded-xl transition-all flex items-center justify-center shrink-0">
                Clear
            </a>
        @endif
    </form>

    <!-- Transaksi List / Table -->
    @if($transactions->isEmpty())
        <div class="bg-surface-container-lowest rounded-2xl p-8 text-center border border-outline-variant/20 shadow-sm flex flex-col items-center justify-center">
            <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary mb-3 shadow-inner">
                <span class="material-symbols-outlined text-[24px] font-bold">eco</span>
            </div>
            <h3 class="text-sm font-bold text-on-surface mb-0.5">Belum Ada Riwayat Setoran</h3>
            <p class="text-xs text-tertiary max-w-sm mb-4 leading-relaxed">
                @if(request()->anyFilled(['search', 'waktu']))
                    Tidak menemukan riwayat setoran yang cocok dengan filter pencarian Anda.
                @else
                    Anda belum melakukan setoran sampah. Mulai setor sampah Anda ke petugas terdekat untuk mengumpulkan poin.
                @endif
            </p>
            @if(request()->anyFilled(['search', 'waktu']))
                <a href="{{ route('warga.transaksi') }}" class="px-4 py-2 bg-primary hover:bg-primary-container text-on-primary text-xs font-bold rounded-xl shadow transition-all flex items-center gap-1 hover:-translate-y-0.5">
                    <span class="material-symbols-outlined text-[14px]">refresh</span>
                    Reset Filter
                </a>
            @else
                <a href="{{ route('warga.dashboard') }}" id="transaksi-empty-cta" class="px-4 py-2 bg-primary hover:bg-primary-container text-on-primary text-xs font-bold rounded-xl shadow transition-all flex items-center gap-1 hover:-translate-y-0.5">
                    <span class="material-symbols-outlined text-[14px]">home</span>
                    Kembali ke Beranda
                </a>
            @endif
        </div>
    @else
        <!-- DESKTOP TABLE VIEW -->
        <div class="hidden sm:block overflow-hidden rounded-xl border border-outline-variant/15 shadow-sm bg-white">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low text-on-surface-variant text-[11px] font-black uppercase tracking-wider border-b border-outline-variant/25">
                        <th class="px-4 py-3 w-28">ID Setoran</th>
                        <th class="px-4 py-3 w-40">Tanggal</th>
                        <th class="px-4 py-3">Rincian Item</th>
                        <th class="px-4 py-3 w-32 text-right">Total Berat</th>
                        <th class="px-4 py-3 w-36 text-right">Total Poin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @foreach($transactions as $trx)
                        <!-- Main Row -->
                        <tr onclick="toggleDetail('setoran-{{ $trx->id }}')" class="hover:bg-surface-container-low/60 cursor-pointer transition-colors odd:bg-white even:bg-surface-container-lowest/30 group">
                            <td class="px-4 py-3 font-extrabold text-xs text-on-surface">#{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-4 py-3 text-xs text-tertiary">{{ $trx->created_at->translatedFormat('d M Y, H:i') }}</td>
                            <td class="px-4 py-3 text-xs text-on-surface-variant truncate max-w-xs font-medium">
                                @foreach($trx->details as $detail)
                                    {{ $detail->wasteCategory->name ?? 'Sampah' }} ({{ $detail->weight }}kg){{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            </td>
                            <td class="px-4 py-3 text-xs text-on-surface font-extrabold text-right">{{ $trx->details->sum('weight') }} kg</td>
                            <td class="px-4 py-3 text-xs text-primary font-black text-right group-hover:text-primary-container transition-colors">
                                +{{ number_format($trx->total_point, 0, ',', '.') }} Poin
                            </td>
                        </tr>
                        <!-- Collapsible Detail Row -->
                        <tr id="detail-setoran-{{ $trx->id }}" class="hidden bg-surface-container-low/20">
                            <td colspan="5" class="px-6 py-4 border-t border-b border-outline-variant/10">
                                <div class="bg-white rounded-lg p-3 border border-outline-variant/15 max-w-3xl">
                                    <p class="text-[10px] font-black text-tertiary uppercase tracking-wider mb-2">Detail Per Item Setoran:</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                        @foreach($trx->details as $detail)
                                            <div class="flex items-center justify-between bg-surface-container-lowest px-3 py-2 rounded border border-outline-variant/10 text-xs">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="w-2 h-2 rounded-full bg-primary shrink-0"></span>
                                                    <span class="font-bold text-on-surface-variant">{{ $detail->wasteCategory->name ?? 'Sampah' }}</span>
                                                </div>
                                                <div class="text-right">
                                                    <span class="font-black text-on-surface">{{ $detail->weight }} kg</span>
                                                    <span class="text-primary font-bold ml-1">({{ number_format($detail->subtotal_point, 0, ',', '.') }} pts)</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- MOBILE LIST VIEW (Under 640px) -->
        <div class="block sm:hidden space-y-2">
            @foreach($transactions as $trx)
                <div onclick="toggleDetail('setoran-{{ $trx->id }}')" class="bg-surface-container-lowest p-3.5 rounded-xl border border-outline-variant/15 hover:border-primary/20 shadow-sm cursor-pointer odd:bg-white even:bg-surface-container-lowest/30">
                    <div class="flex justify-between items-center mb-1">
                        <span class="font-bold text-xs text-on-surface">Setoran #{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</span>
                        <span class="text-xs font-black text-primary">+{{ number_format($trx->total_point, 0, ',', '.') }} Poin</span>
                    </div>
                    <div class="flex justify-between items-center text-[10px] text-tertiary">
                        <span>{{ $trx->created_at->translatedFormat('d M Y, H:i') }}</span>
                        <span>{{ $trx->details->sum('weight') }} kg</span>
                    </div>
                    
                    <!-- Collapsible mobile details -->
                    <div id="mobile-detail-setoran-{{ $trx->id }}" class="hidden mt-3 pt-3 border-t border-outline-variant/10 text-xs text-on-surface-variant space-y-2 animate-fade-in-up">
                        <p class="text-[9px] font-black text-tertiary uppercase tracking-wider">Detail Per Item:</p>
                        @foreach($trx->details as $detail)
                            <div class="flex justify-between items-center bg-white px-2.5 py-1.5 rounded border border-outline-variant/10 text-[11px]">
                                <span class="font-medium text-on-surface-variant">{{ $detail->wasteCategory->name ?? 'Sampah' }} ({{ $detail->weight }} kg)</span>
                                <span class="font-extrabold text-primary">+{{ number_format($detail->subtotal_point, 0, ',', '.') }} pts</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        @if($transactions->hasPages())
            <div class="mt-6">
                {{ $transactions->links() }}
            </div>
        @endif
    @endif
</section>
@endsection

@section('scripts')
<script>
    function toggleDetail(id) {
        // Toggle desktop detail row
        const desktopRow = document.getElementById('detail-' + id);
        if (desktopRow) {
            desktopRow.classList.toggle('hidden');
        }
        // Toggle mobile card detail
        const mobileDetail = document.getElementById('mobile-detail-' + id);
        if (mobileDetail) {
            mobileDetail.classList.toggle('hidden');
        }
    }
</script>
@endsection
