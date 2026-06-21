@extends('layouts.app')

@section('title', 'Beranda - SmartSort')

@section('content')

<!-- Welcome Title Section -->
<section class="mb-stack-lg animate-fade-in-up pt-stack-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">Selamat Datang, {{ $user->name }}</h1>
        <div class="flex items-center gap-2 text-tertiary mt-1">
            <span class="material-symbols-outlined text-[18px]">location_on</span>
            <span class="font-body-md text-sm text-tertiary">{{ $user->rt_rw ?? 'Data RT/RW belum diatur' }}</span>
        </div>
    </div>
</section>

<!-- Saldo Poin Hero Card -->
<section class="mb-stack-lg">
    <div class="premium-gradient rounded-3xl p-8 text-on-primary relative overflow-hidden shadow-xl border border-white/10">
        <!-- Background light blobs -->
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 -left-12 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-2">
                    <span class="font-label-sm text-xs uppercase tracking-wider opacity-85">Saldo Tabungan Anda</span>
                    <span class="material-symbols-outlined text-[18px] opacity-80" style="font-variation-settings: 'FILL' 1;">eco</span>
                </div>
                <div id="saldo-poin-text" class="font-headline-lg text-4xl sm:text-5xl font-black mb-3 tracking-tight drop-shadow-sm">{{ number_format($user->saldo_poin, 0, ',', '.') }} Poin</div>
                <div class="font-body-md text-xs opacity-90 bg-black/15 inline-flex items-center gap-1.5 px-4 py-2 rounded-2xl backdrop-blur-md border border-white/10">
                    <span class="material-symbols-outlined text-[14px]">payments</span>
                    Setara dengan <span id="saldo-rupiah-text" class="font-extrabold text-sm text-yellow-300">Rp{{ number_format($user->saldo_poin, 0, ',', '.') }}</span>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="flex flex-row md:flex-col gap-2.5 w-full md:w-auto">
                <a href="{{ route('warga.katalog') }}" class="flex-1 md:flex-none h-11 px-5 bg-white text-primary hover:bg-emerald-50 rounded-2xl font-bold text-xs transition-all shadow-sm flex items-center justify-center gap-1.5 hover:-translate-y-0.5 active:scale-95">
                    <span class="material-symbols-outlined text-[16px] font-bold">shopping_basket</span>
                    Tukar Poin
                </a>
                <a href="{{ route('warga.redemption') }}" class="flex-1 md:flex-none h-11 px-5 bg-white/10 hover:bg-white/20 border border-white/20 text-white rounded-2xl font-bold text-xs transition-all flex items-center justify-center gap-1.5 hover:-translate-y-0.5 active:scale-95">
                    <span class="material-symbols-outlined text-[16px] font-bold">history</span>
                    Riwayat Penukaran
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Grid Ringkasan Aktivitas (4 columns) -->
<section class="mb-stack-lg">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Setor (kg) -->
        <div class="bg-white rounded-2xl p-5 border border-outline-variant/30 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">Total Sampah</span>
                <div class="p-2 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-100/50">
                    <span class="material-symbols-outlined text-[20px]">scale</span>
                </div>
            </div>
            <div>
                <div class="text-2xl font-black text-on-surface">{{ number_format($totalSampah, 1, ',', '.') }} <span class="text-xs font-semibold text-tertiary">Kg</span></div>
                <p class="text-[11px] text-tertiary mt-1">Sampah terkumpul</p>
            </div>
        </div>

        <!-- Total Transaksi -->
        <div class="bg-white rounded-2xl p-5 border border-outline-variant/30 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">Transaksi</span>
                <div class="p-2 bg-primary/10 text-primary rounded-xl border border-primary/20">
                    <span class="material-symbols-outlined text-[20px]">recycling</span>
                </div>
            </div>
            <div>
                <div class="text-2xl font-black text-on-surface">{{ $totalTransaksi }} <span class="text-xs font-semibold text-tertiary">Kali</span></div>
                <p class="text-[11px] text-tertiary mt-1">Setor sampah</p>
            </div>
        </div>

        <!-- Total Penukaran -->
        <div class="bg-white rounded-2xl p-5 border border-outline-variant/30 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">Penukaran</span>
                <div class="p-2 bg-secondary/15 text-secondary rounded-xl border border-secondary/20">
                    <span class="material-symbols-outlined text-[20px]">shopping_bag</span>
                </div>
            </div>
            <div>
                <div class="text-2xl font-black text-on-surface">{{ $totalRedemption }} <span class="text-xs font-semibold text-tertiary">Kali</span></div>
                <p class="text-[11px] text-tertiary mt-1">Klaim barang sembako</p>
            </div>
        </div>

        <!-- Poin Terpakai -->
        <div class="bg-white rounded-2xl p-5 border border-outline-variant/30 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">Poin Terpakai</span>
                <div class="p-2 bg-red-50 text-red-700 rounded-xl border border-red-100/50">
                    <span class="material-symbols-outlined text-[20px]">shopping_cart_checkout</span>
                </div>
            </div>
            <div>
                <div class="text-2xl font-black text-on-surface">{{ number_format($totalPoinDipakai, 0, ',', '.') }} <span class="text-xs font-semibold text-tertiary">Pts</span></div>
                <p class="text-[11px] text-tertiary mt-1">Total penukaran disetujui</p>
            </div>
        </div>
    </div>
</section>

<!-- Content Grid (2 columns on desktop) -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-stack-lg">
    <!-- Left Column (Status & Timeline) -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- Status Penukaran Terbaru -->
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary font-bold">sell</span>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">Status Penukaran Terbaru</h2>
                </div>
                
                <!-- Filter form -->
                <form action="{{ route('warga.dashboard') }}" method="GET" class="flex gap-2">
                    <select name="status" class="bg-white border border-outline-variant/50 text-xs rounded-xl px-3 py-2 outline-none focus:ring-2 focus:ring-primary/20 shadow-sm" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Ready</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    <select name="sort" class="bg-white border border-outline-variant/50 text-xs rounded-xl px-3 py-2 outline-none focus:ring-2 focus:ring-primary/20 shadow-sm" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort') !== 'oldest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama</option>
                    </select>
                </form>
            </div>
            
            <div class="flex flex-col gap-4">
                @forelse($dashboardRedemptions as $redemption)
                <div id="redemption-card-{{ $redemption->id }}" class="bg-white rounded-2xl p-6 border border-outline-variant/20 shadow-sm hover:shadow-md transition-all duration-200">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-xs font-bold text-primary">#RED-{{ str_pad($redemption->id, 5, '0', STR_PAD_LEFT) }}</span>
                                <span class="text-[10px] text-tertiary font-semibold flex items-center gap-0.5">
                                    <span class="material-symbols-outlined text-[12px]">calendar_today</span>
                                    {{ $redemption->created_at->format('d M Y, H:i') }}
                                </span>
                            </div>
                            <h4 class="font-bold text-on-surface mt-2 text-sm sm:text-base">
                                {{ $redemption->details->first()->reward->name ?? 'Sembako' }} 
                                @if($redemption->details->count() > 1)
                                    <span class="text-xs text-tertiary font-normal">dan {{ $redemption->details->count() - 1 }} barang lainnya</span>
                                @endif
                            </h4>
                        </div>
                        
                        <div class="redemption-status-container">
                            @php
                                $statusColor = 'bg-slate-100 text-slate-700 border-slate-200';
                                $statusIcon = 'check_circle';
                                $statusText = 'Tidak Diketahui';
                                $statusMessage = '';

                                if ($redemption->status === 'pending') {
                                    $statusColor = 'bg-yellow-50 text-yellow-700 border-yellow-200';
                                    $statusIcon = 'schedule';
                                    $statusText = 'Pending';
                                    $statusMessage = 'Menunggu persetujuan dan verifikasi admin.';
                                } elseif ($redemption->status === 'approved') {
                                    $statusColor = 'bg-blue-50 text-blue-700 border-blue-200';
                                    $statusIcon = 'thumb_up';
                                    $statusText = 'Approved';
                                    $statusMessage = 'Disetujui admin, silakan persiapkan diri sesuai jadwal.';
                                } elseif ($redemption->status === 'ready') {
                                    $statusColor = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                    $statusIcon = 'inventory_2';
                                    $statusText = 'Ready';
                                    $statusMessage = 'Barang siap diambil di loket Balai Desa.';
                                } elseif ($redemption->status === 'completed') {
                                    $statusColor = 'bg-slate-50 text-slate-600 border-slate-200';
                                    $statusIcon = 'done_all';
                                    $statusText = 'Completed';
                                    $statusMessage = 'Barang telah berhasil diserahkan ke warga.';
                                } elseif ($redemption->status === 'rejected') {
                                    $statusColor = 'bg-red-50 text-red-700 border-red-200';
                                    $statusIcon = 'cancel';
                                    $statusText = 'Rejected';
                                    $statusMessage = 'Penukaran ditolak: ' . ($redemption->catatan_admin ?? 'Saldo kurang/stok habis.');
                                }
                            @endphp
                            <div class="redemption-badge inline-flex items-center gap-1.5 px-3 py-1 rounded-full border {{ $statusColor }} text-xs font-bold shadow-sm">
                                <span class="redemption-icon material-symbols-outlined text-[14px] font-bold">{{ $statusIcon }}</span>
                                <span class="redemption-text uppercase tracking-wider">{{ $statusText }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="redemption-message-container bg-surface-container-low/40 rounded-xl p-4 flex flex-col gap-2">
                        <div class="flex items-start gap-2 text-xs text-on-surface-variant leading-relaxed">
                            <span class="material-symbols-outlined text-tertiary text-[18px] mt-0.5">info</span>
                            <p class="redemption-message font-medium">{{ $statusMessage }}</p>
                        </div>
                        
                        @if(in_array($redemption->status, ['approved', 'ready']) && $redemption->tanggal_ambil)
                        <div class="redemption-schedule-container flex items-start gap-2 mt-2.5 pt-2.5 border-t border-outline-variant/20 text-xs">
                            <span class="material-symbols-outlined text-primary text-[18px]">event_available</span>
                            <div>
                                <p class="font-semibold text-on-surface-variant">Jadwal Pengambilan Barang:</p>
                                <p class="redemption-schedule-date font-extrabold text-primary mt-0.5 text-sm">
                                    Silakan ambil pada: {{ \Carbon\Carbon::parse($redemption->tanggal_ambil)->translatedFormat('d F Y') }}
                                </p>
                            </div>
                        </div>
                        @else
                        <div class="redemption-schedule-container hidden mt-2.5 pt-2.5 border-t border-outline-variant/20 text-xs">
                            <div class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-primary text-[18px]">event_available</span>
                                <div>
                                    <p class="font-semibold text-on-surface-variant">Jadwal Pengambilan Barang:</p>
                                    <p class="redemption-schedule-date font-extrabold text-primary mt-0.5 text-sm"></p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-2xl p-8 border border-outline-variant/30 text-center flex flex-col items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined text-4xl text-outline-variant mb-2">inbox</span>
                    <p class="text-xs text-on-surface-variant font-semibold">Tidak ada riwayat penukaran aktif saat ini.</p>
                </div>
                @endforelse
                
                @if($dashboardRedemptions->hasPages())
                <div class="mt-4">
                    {{ $dashboardRedemptions->links() }}
                </div>
                @endif
            </div>
        </div>

        <!-- Timeline Aktivitas Terbaru -->
        <div class="space-y-4">
            <h2 class="text-lg font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary font-bold">history_toggle_off</span>
                Timeline Aktivitas Terbaru
            </h2>
            
            <div class="bg-white rounded-2xl p-6 border border-outline-variant/20 shadow-sm relative">
                @if($timeline->isEmpty())
                    <div class="text-center py-8 text-xs text-on-surface-variant font-semibold flex flex-col items-center justify-center">
                        <span class="material-symbols-outlined text-4xl text-outline-variant mb-2">pending_actions</span>
                        Belum ada riwayat aktivitas setor sampah maupun penukaran sembako.
                    </div>
                @else
                    <div class="relative border-l-2 border-outline-variant/35 ml-4 pl-6 space-y-6">
                        @foreach($timeline as $item)
                            @php
                                $isSetor = $item['type'] === 'setor';
                                $iconColor = $isSetor ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-100';
                                $icon = $isSetor ? 'compost' : 'shopping_bag';
                                $badgePoints = $isSetor ? '+' . number_format($item['points']) : '-' . number_format($item['points']);
                                $pointsColor = $isSetor ? 'text-emerald-600' : 'text-error';
                            @endphp
                            
                            <div class="relative">
                                <!-- Bullet Icon on Line -->
                                <span class="absolute left-[-37px] top-0 h-container-margin w-container-margin rounded-full {{ $iconColor }} border-2 border-white flex items-center justify-center shadow-sm">
                                    <span class="material-symbols-outlined text-[12px] font-bold">{{ $icon }}</span>
                                </span>
                                
                                <div>
                                    <span class="text-[10px] text-tertiary font-bold uppercase tracking-wide block mb-1">
                                        {{ $item['created_at']->diffForHumans() }}
                                    </span>
                                    <div class="flex items-start justify-between gap-3">
                                        <p class="font-body-md text-xs sm:text-sm text-on-surface font-semibold leading-relaxed">
                                            {{ $item['description'] }}
                                        </p>
                                        <span class="font-black text-sm whitespace-nowrap {{ $pointsColor }}">
                                            {{ $badgePoints }} Pts
                                        </span>
                                    </div>
                                    @if($isSetor && isset($item['weight']))
                                        <span class="inline-block mt-1 text-[11px] text-tertiary bg-emerald-50/50 border border-emerald-100/30 px-2 py-0.5 rounded-lg">
                                            Total berat: {{ number_format($item['weight'], 1) }} kg
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column (Recommendations & Latest Deposit) -->
    <div class="space-y-6">
        <!-- Rekomendasi Reward -->
        <div class="space-y-4">
            <h2 class="text-lg font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary font-bold">recommend</span>
                Rekomendasi Tukar Reward
            </h2>
            
            <div class="bg-white rounded-2xl p-5 border border-outline-variant/20 shadow-sm space-y-4">
                @forelse($recommendedRewards as $recReward)
                    @php
                        $recNameLower = strtolower($recReward->name);
                        $recIcon = 'shopping_bag';
                        if (str_contains($recNameLower, 'beras')) $recIcon = 'rice_bowl';
                        elseif (str_contains($recNameLower, 'minyak')) $recIcon = 'oil_barrel';
                        elseif (str_contains($recNameLower, 'gula')) $recIcon = 'grain';
                        elseif (str_contains($recNameLower, 'tepung')) $recIcon = 'bakery_dining';
                        elseif (str_contains($recNameLower, 'mie')) $recIcon = 'ramen_dining';
                        elseif (str_contains($recNameLower, 'sabun') || str_contains($recNameLower, 'cuci') || str_contains($recNameLower, 'deterjen')) $recIcon = 'soap';
                    @endphp
                    <div class="flex items-center justify-between gap-3 p-3 bg-surface-container-low/40 border border-outline-variant/15 rounded-xl transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl bg-primary/5 flex items-center justify-center text-primary border border-primary/10">
                                <span class="material-symbols-outlined text-[20px]">{{ $recIcon }}</span>
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-xs text-on-surface line-clamp-1 group-hover:text-primary transition-colors">{{ $recReward->name }}</h4>
                                <p class="text-[10px] text-primary font-black mt-0.5">{{ number_format($recReward->point_cost, 0, ',', '.') }} Pts</p>
                            </div>
                        </div>
                        
                        <a href="{{ route('warga.katalog') }}" class="h-8 px-3 bg-primary text-on-primary hover:bg-primary-container text-[10px] font-bold rounded-lg transition-all flex items-center justify-center gap-1 whitespace-nowrap active:scale-95">
                            Tukar
                            <span class="material-symbols-outlined text-[12px]">chevron_right</span>
                        </a>
                    </div>
                @empty
                    <div class="text-center py-6 text-xs text-on-surface-variant font-medium">
                        Kumpulkan poin untuk membuka rekomendasi reward menarik!
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Setoran Terakhir -->
        <div class="space-y-4">
            <h2 class="text-lg font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary font-bold">compost</span>
                Setoran Sampah Terakhir
            </h2>
            
            <div class="bg-white rounded-2xl p-5 border border-outline-variant/20 shadow-sm flex flex-col justify-between relative overflow-hidden">
                @if($latestTransaction)
                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-10 w-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                            <span class="material-symbols-outlined text-[20px] font-bold">recycling</span>
                        </div>
                        <div>
                            <p class="text-[10px] text-tertiary font-bold uppercase tracking-wide">{{ $latestTransaction->created_at->diffForHumans() }}</p>
                            <p class="font-bold text-xs text-on-surface mt-0.5">Penambahan Poin Sukses</p>
                        </div>
                    </div>
                    
                    <div class="bg-surface-container-low/40 rounded-xl p-3.5 space-y-2 mt-auto">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-on-surface-variant font-medium">Total Berat</span>
                            <!-- FIX BUG: Hitung sum weight dari detail relasi agar tidak menampilkan 0,0 kg -->
                            <span class="font-bold text-on-surface">{{ number_format($latestTransaction->details->sum('weight'), 1, ',', '.') }} kg</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-outline-variant/20 text-xs">
                            <span class="text-on-surface-variant font-medium">Poin Diperoleh</span>
                            <span class="font-black text-primary text-sm">+{{ number_format($latestTransaction->total_point, 0, ',', '.') }} Pts</span>
                        </div>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center text-center py-8 text-on-surface-variant opacity-60">
                        <span class="material-symbols-outlined text-3xl mb-1.5">history_toggle_off</span>
                        <p class="text-xs font-semibold">Belum ada riwayat setoran.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // 1. Notification API Permission
    if ("Notification" in window) {
        if (Notification.permission !== "granted" && Notification.permission !== "denied") {
            Notification.requestPermission();
        }
    }

    // Polling Control
    let pollingInterval;
    const POLLING_TIME = 10000; // 10 seconds
    const API_URL = "{{ route('warga.api.status') }}";

    // 2. Format Number Utility
    const formatNumber = (num) => new Intl.NumberFormat('id-ID').format(num);

    // Update DOM Helper
    const updateDashboardDOM = (data) => {
        // Update Saldo
        const saldoPoin = document.getElementById('saldo-poin-text');
        const saldoRupiah = document.getElementById('saldo-rupiah-text');
        
        if(saldoPoin && data.saldo !== undefined) saldoPoin.textContent = formatNumber(data.saldo) + " Poin";
        if(saldoRupiah && data.saldo !== undefined) saldoRupiah.textContent = "Rp" + formatNumber(data.saldo);

        // Update Redemptions
        if(data.redemptions && Array.isArray(data.redemptions)) {
            data.redemptions.forEach(item => {
                const card = document.getElementById('redemption-card-' + item.raw_id);
                if (card) {
                    const badge = card.querySelector('.redemption-badge');
                    const icon = card.querySelector('.redemption-icon');
                    const text = card.querySelector('.redemption-text');
                    const msg = card.querySelector('.redemption-message');
                    const schedContainer = card.querySelector('.redemption-schedule-container');
                    const schedDate = card.querySelector('.redemption-schedule-date');

                    // Detach all status colors
                    badge.className = "redemption-badge inline-flex items-center gap-1.5 px-3 py-1 rounded-full border font-label-sm font-bold";
                    
                    let statusColor = 'bg-slate-100 text-slate-700 border-slate-200';
                    let statusIcon = 'check_circle';
                    let statusText = 'Unknown';
                    let statusMessage = '';

                    if (item.status === 'pending') {
                        statusColor = 'bg-yellow-50 text-yellow-700 border-yellow-200';
                        statusIcon = 'schedule';
                        statusText = 'Pending';
                        statusMessage = 'Menunggu persetujuan dan verifikasi admin.';
                        schedContainer.classList.add('hidden');
                    } else if (item.status === 'approved') {
                        statusColor = 'bg-blue-50 text-blue-700 border-blue-200';
                        statusIcon = 'thumb_up';
                        statusText = 'Approved';
                        statusMessage = 'Disetujui admin, silakan persiapkan diri sesuai jadwal.';
                        schedContainer.classList.remove('hidden');
                    } else if (item.status === 'ready') {
                        statusColor = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                        statusIcon = 'inventory_2';
                        statusText = 'Ready';
                        statusMessage = 'Barang siap diambil di loket Balai Desa.';
                        schedContainer.classList.remove('hidden');
                    } else if (item.status === 'completed') {
                        statusColor = 'bg-slate-50 text-slate-600 border-slate-200';
                        statusIcon = 'done_all';
                        statusText = 'Completed';
                        statusMessage = 'Barang telah berhasil diserahkan ke warga.';
                        schedContainer.classList.add('hidden');
                    } else if (item.status === 'rejected') {
                        statusColor = 'bg-red-50 text-red-700 border-red-200';
                        statusIcon = 'cancel';
                        statusText = 'Rejected';
                        statusMessage = 'Penukaran ditolak.';
                        schedContainer.classList.add('hidden');
                    }

                    badge.className += " " + statusColor;
                    icon.textContent = statusIcon;
                    text.textContent = statusText;
                    msg.textContent = statusMessage;

                    if (item.tanggal_ambil) {
                        schedDate.textContent = "Silakan ambil pada: " + item.tanggal_ambil;
                    }
                }
            });
        }
    };

    // 3. Fetch Data & Trigger Notifications
    const fetchStatus = async () => {
        try {
            const response = await fetch(API_URL, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            if (response.ok) {
                const data = await response.json();
                
                // Compare state for notifications
                const storedState = localStorage.getItem('smartsort_redemptions');
                const oldRedemptions = storedState ? JSON.parse(storedState) : [];
                
                data.redemptions.forEach(newRedemption => {
                    const oldRedemption = oldRedemptions.find(r => r.raw_id === newRedemption.raw_id);
                    
                    if (oldRedemption && oldRedemption.status !== newRedemption.status) {
                        // Status Changed! Show Notification
                        if ("Notification" in window && Notification.permission === "granted") {
                            let notifBody = "";
                            if (newRedemption.status === 'approved') {
                                notifBody = `Penukaran #${newRedemption.id} telah disetujui!`;
                            } else if (newRedemption.status === 'ready') {
                                notifBody = `Penukaran #${newRedemption.id} siap diambil!`;
                            } else if (newRedemption.status === 'completed') {
                                notifBody = `Penukaran #${newRedemption.id} telah selesai.`;
                            }
                            
                            if (notifBody) {
                                new Notification("SmartSort Info", {
                                    body: notifBody,
                                    icon: "/favicon.ico"
                                });
                            }
                        }
                    }
                });

                // Update LocalStorage State
                localStorage.setItem('smartsort_redemptions', JSON.stringify(data.redemptions));

                // Update DOM
                updateDashboardDOM(data);
            }
        } catch (error) {
            console.error("Error fetching realtime status:", error);
        }
    };

    // Start/Stop Polling Functions
    const startPolling = () => {
        if (!pollingInterval) {
            pollingInterval = setInterval(fetchStatus, POLLING_TIME);
        }
    };

    const stopPolling = () => {
        if (pollingInterval) {
            clearInterval(pollingInterval);
            pollingInterval = null;
        }
    };

    // 4. Page Visibility API
    document.addEventListener("visibilitychange", () => {
        if (document.visibilityState === 'visible') {
            fetchStatus();
            startPolling();
        } else {
            stopPolling();
        }
    });

    // Initial Start
    fetchStatus(); 
    startPolling();
});
</script>
@endsection
