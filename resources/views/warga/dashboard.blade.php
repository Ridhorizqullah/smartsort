@extends('layouts.app')

@section('title', 'Beranda - SmartSort')

@section('content')
<section class="mb-stack-lg animate-fade-in-up pt-stack-md">
    <h1 class="font-headline-lg text-headline-lg text-on-surface mb-stack-sm tracking-tight">Selamat Datang, {{ $user->name }}</h1>
    <div class="flex items-center gap-2 text-tertiary">
        <span class="material-symbols-outlined text-[18px]">location_on</span>
        <span class="font-body-md text-body-md">{{ $user->rt_rw ?? 'Data RT/RW belum diatur' }}</span>
    </div>
</section>

<!-- Saldo Poin Card -->
<section class="mb-stack-lg">
    <div class="premium-gradient rounded-2xl p-8 text-on-primary relative overflow-hidden shadow-lg border border-white/20">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-secondary-container/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 -left-12 w-48 h-48 bg-primary-container/20 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-end gap-stack-lg">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-stack-sm">
                    <span class="font-label-md text-label-md opacity-90">Saldo Anda</span>
                    <span class="material-symbols-outlined text-[20px] opacity-80" style="font-variation-settings: 'FILL' 1;">eco</span>
                </div>
                <div id="saldo-poin-text" class="font-headline-lg text-display-lg mb-stack-sm tracking-tight drop-shadow-sm">{{ number_format($user->saldo_poin, 0, ',', '.') }} Poin</div>
                <div class="font-body-md text-body-md opacity-90 bg-black/10 inline-block px-4 py-1.5 rounded-full backdrop-blur-sm border border-white/10">
                    Setara dengan <span id="saldo-rupiah-text" class="font-bold">Rp{{ number_format($user->saldo_poin, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Status Penukaran Terbaru -->
<section class="mb-stack-lg">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">sell</span>
            <h2 class="font-headline-sm text-headline-sm text-on-surface">Status Penukaran</h2>
        </div>
        
        <form action="{{ route('warga.dashboard') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
            <select name="status" class="bg-surface-container-low border border-outline-variant/30 text-sm rounded-lg px-3 py-2 focus:ring-primary focus:border-primary outline-none" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Ready</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <select name="sort" class="bg-surface-container-low border border-outline-variant/30 text-sm rounded-lg px-3 py-2 focus:ring-primary focus:border-primary outline-none" onchange="this.form.submit()">
                <option value="newest" {{ request('sort') !== 'oldest' ? 'selected' : '' }}>Terbaru</option>
                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama</option>
            </select>
        </form>
    </div>
    
    <div class="flex flex-col gap-4">
        @forelse($dashboardRedemptions as $redemption)
        <div id="redemption-card-{{ $redemption->id }}" class="bg-surface-container-lowest rounded-2xl p-6 border border-outline-variant/30 shadow-sm transition-all hover:shadow-md">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                <div>
                    <p class="font-label-sm text-label-sm text-on-surface-variant mb-1">ID Penukaran: #RED-{{ str_pad($redemption->id, 5, '0', STR_PAD_LEFT) }}</p>
                    <p class="font-body-md text-on-surface font-medium">
                        {{ $redemption->details->first()->reward->name ?? 'Sembako' }} 
                        @if($redemption->details->count() > 1)
                            dan {{ $redemption->details->count() - 1 }} item lainnya
                        @endif
                    </p>
                </div>
                
                <div class="redemption-status-container">
                    @php
                        $statusColor = 'bg-slate-100 text-slate-700 border-slate-200';
                        $statusIcon = 'check_circle';
                        $statusText = 'Status Tidak Diketahui';
                        $statusMessage = '';

                        if ($redemption->status === 'pending') {
                            $statusColor = 'bg-yellow-50 text-yellow-700 border-yellow-200';
                            $statusIcon = 'schedule';
                            $statusText = 'Pending';
                            $statusMessage = 'Menunggu persetujuan admin.';
                        } elseif ($redemption->status === 'approved') {
                            $statusColor = 'bg-blue-50 text-blue-700 border-blue-200';
                            $statusIcon = 'thumb_up';
                            $statusText = 'Approved';
                            $statusMessage = 'Disetujui admin, sedang dipersiapkan.';
                        } elseif ($redemption->status === 'ready') {
                            $statusColor = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                            $statusIcon = 'inventory_2';
                            $statusText = 'Ready';
                            $statusMessage = 'Siap diambil di Balai Desa.';
                        } elseif ($redemption->status === 'completed') {
                            $statusColor = 'bg-slate-50 text-slate-600 border-slate-200';
                            $statusIcon = 'done_all';
                            $statusText = 'Completed';
                            $statusMessage = 'Sudah diambil.';
                        } elseif ($redemption->status === 'rejected') {
                            $statusColor = 'bg-red-50 text-red-700 border-red-200';
                            $statusIcon = 'cancel';
                            $statusText = 'Rejected';
                            $statusMessage = 'Penukaran ditolak.';
                        }
                    @endphp
                    <div class="redemption-badge inline-flex items-center gap-1.5 px-3 py-1 rounded-full border {{ $statusColor }} font-label-sm font-bold">
                        <span class="redemption-icon material-symbols-outlined text-[16px]">{{ $statusIcon }}</span>
                        <span class="redemption-text">{{ $statusText }}</span>
                    </div>
                </div>
            </div>
            
            <div class="redemption-message-container bg-surface-container-low/50 rounded-xl p-4 flex flex-col gap-2">
                <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-on-surface-variant text-[20px]">info</span>
                    <p class="redemption-message font-body-md text-on-surface">{{ $statusMessage }}</p>
                </div>
                
                @if(in_array($redemption->status, ['approved', 'ready']) && $redemption->tanggal_ambil)
                <div class="redemption-schedule-container flex items-start gap-2 mt-2 pt-2 border-t border-outline-variant/30">
                    <span class="material-symbols-outlined text-primary text-[20px]">event_available</span>
                    <div>
                        <p class="font-label-sm text-on-surface-variant">Jadwal Pengambilan:</p>
                        <p class="redemption-schedule-date font-body-md font-semibold text-primary">
                            Silakan ambil pada: {{ \Carbon\Carbon::parse($redemption->tanggal_ambil)->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </div>
                @else
                <!-- Placeholder if schedule gets added via API later -->
                <div class="redemption-schedule-container hidden mt-2 pt-2 border-t border-outline-variant/30">
                    <div class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-primary text-[20px]">event_available</span>
                        <div>
                            <p class="font-label-sm text-on-surface-variant">Jadwal Pengambilan:</p>
                            <p class="redemption-schedule-date font-body-md font-semibold text-primary"></p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-surface-container-lowest rounded-2xl p-8 border border-outline-variant/30 text-center flex flex-col items-center shadow-sm">
            <span class="material-symbols-outlined text-4xl text-outline-variant mb-2">inbox</span>
            <p class="text-on-surface-variant">Belum ada riwayat penukaran poin.</p>
        </div>
        @endforelse
        
        @if($dashboardRedemptions->hasPages())
        <div class="mt-4">
            {{ $dashboardRedemptions->links() }}
        </div>
        @endif
    </div>
</section>

<!-- Stats & Riwayat Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-stack-lg mb-stack-lg">
    
    <!-- Kolom Kiri: Stats (2/3 width on LG) -->
    <div class="lg:col-span-2 flex flex-col gap-stack-md">
        <h2 class="font-headline-sm text-headline-sm text-on-surface mb-2">Statistik Aktivitas</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-gutter">
            <!-- Total Transaksi -->
            <div class="bg-surface-container-lowest rounded-2xl p-6 flex flex-col justify-between group border border-outline-variant/30 shadow-sm">
                <div class="flex items-start justify-between mb-stack-md">
                    <div class="p-3 bg-primary/10 rounded-xl text-primary">
                        <span class="material-symbols-outlined">recycling</span>
                    </div>
                </div>
                <div>
                    <h3 class="font-label-md text-label-md text-on-surface-variant mb-1">Total Setor Sampah</h3>
                    <div class="font-headline-md text-headline-md text-on-surface">{{ $totalTransaksi }} <span class="text-label-md text-on-surface-variant font-normal">Transaksi</span></div>
                    <p class="text-body-sm text-on-surface-variant mt-1">Total berat: {{ number_format($totalSampah, 1, ',', '.') }} kg</p>
                </div>
            </div>
            
            <!-- Total Penukaran -->
            <div class="bg-surface-container-lowest rounded-2xl p-6 flex flex-col justify-between group border border-outline-variant/30 shadow-sm">
                <div class="flex items-start justify-between mb-stack-md">
                    <div class="p-3 bg-secondary/10 rounded-xl text-secondary">
                        <span class="material-symbols-outlined">shopping_bag</span>
                    </div>
                </div>
                <div>
                    <h3 class="font-label-md text-label-md text-on-surface-variant mb-1">Total Penukaran</h3>
                    <div class="font-headline-md text-headline-md text-on-surface">{{ $totalRedemption }} <span class="text-label-md text-on-surface-variant font-normal">Kali</span></div>
                    <p class="text-body-sm text-on-surface-variant mt-1">Total poin terpakai: {{ number_format($totalPoinDipakai, 0, ',', '.') }} pts</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Aktivitas Terakhir (1/3 width on LG) -->
    <div class="flex flex-col gap-stack-md">
        <h2 class="font-headline-sm text-headline-sm text-on-surface mb-2">Setor Terakhir</h2>
        
        <div class="bg-surface-container-lowest rounded-2xl p-6 border border-outline-variant/30 shadow-sm h-full flex flex-col">
            @if($latestTransaction)
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                        <span class="material-symbols-outlined">compost</span>
                    </div>
                    <div>
                        <p class="font-label-sm text-on-surface-variant">{{ $latestTransaction->created_at->diffForHumans() }}</p>
                        <p class="font-label-md text-on-surface">Penambahan Poin</p>
                    </div>
                </div>
                
                <div class="bg-surface-container-low/50 rounded-xl p-4 mt-auto">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-body-sm text-on-surface-variant">Total Berat</span>
                        <span class="font-label-md text-on-surface">{{ number_format($latestTransaction->total_weight, 1, ',', '.') }} kg</span>
                    </div>
                    <div class="flex justify-between items-center pt-2 border-t border-outline-variant/30">
                        <span class="text-body-sm text-on-surface-variant">Poin Didapat</span>
                        <span class="font-label-md text-primary">+{{ number_format($latestTransaction->total_points, 0, ',', '.') }} pts</span>
                    </div>
                </div>
            @else
                <div class="flex flex-col items-center justify-center text-center h-full text-on-surface-variant opacity-60">
                    <span class="material-symbols-outlined text-4xl mb-2">history_toggle_off</span>
                    <p class="font-body-sm">Belum ada riwayat setoran.</p>
                </div>
            @endif
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
                        statusMessage = 'Menunggu persetujuan admin.';
                        schedContainer.classList.add('hidden');
                    } else if (item.status === 'approved') {
                        statusColor = 'bg-blue-50 text-blue-700 border-blue-200';
                        statusIcon = 'thumb_up';
                        statusText = 'Approved';
                        statusMessage = 'Disetujui admin, sedang dipersiapkan.';
                        schedContainer.classList.remove('hidden');
                    } else if (item.status === 'ready') {
                        statusColor = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                        statusIcon = 'inventory_2';
                        statusText = 'Ready';
                        statusMessage = 'Siap diambil di Balai Desa.';
                        schedContainer.classList.remove('hidden');
                    } else if (item.status === 'completed') {
                        statusColor = 'bg-slate-50 text-slate-600 border-slate-200';
                        statusIcon = 'done_all';
                        statusText = 'Completed';
                        statusMessage = 'Sudah diambil.';
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
                                    icon: "/favicon.ico" // You can change this if you have an icon
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
            // Tab is active: fetch immediately then resume polling
            fetchStatus();
            startPolling();
        } else {
            // Tab is hidden: pause polling to save resources
            stopPolling();
        }
    });

    // Initial Start
    // Store initial state into localStorage so we don't trigger notification on first load
    fetchStatus(); 
    startPolling();
});
</script>
@endsection
