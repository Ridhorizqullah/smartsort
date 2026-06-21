@extends('layouts.app')

@section('title', 'Riwayat Penukaran - SmartSort')

@section('content')
<section class="mb-stack-lg animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col gap-1 mb-stack-md">
        <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">Riwayat Penukaran Poin</h1>
        <p class="font-body-md text-sm text-tertiary">Pantau status penukaran reward Anda.</p>
    </div>

    <!-- Statistik Ringkas (Compact Inline Bar) -->
    <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-xs text-on-surface-variant font-medium bg-surface-container-lowest px-4 py-2.5 rounded-xl border border-outline-variant/20 shadow-sm mb-6">
        <span class="flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px] text-primary font-bold">local_mall</span>
            <span>Total: <strong class="text-on-surface font-extrabold">{{ number_format($totalPenukaran) }}</strong></span>
        </span>
        <span class="text-outline-variant/40">•</span>
        <span class="flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px] text-error font-bold">receipt_long</span>
            <span>Poin Dipakai: <strong class="text-error font-extrabold">-{{ number_format($totalPoinDipakai) }} Poin</strong></span>
        </span>
        <span class="text-outline-variant/40">•</span>
        <span class="flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px] text-amber-600 font-bold">pending</span>
            <span>Pending: <strong class="text-amber-600 font-extrabold">{{ number_format($penukaranPending) }}</strong></span>
        </span>
        <span class="text-outline-variant/40">•</span>
        <span class="flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px] text-emerald-600 font-bold">task_alt</span>
            <span>Selesai: <strong class="text-emerald-600 font-extrabold">{{ number_format($penukaranSelesai) }}</strong></span>
        </span>
    </div>

    <!-- Filter Form -->
    <form action="{{ route('warga.redemption') }}" method="GET" class="flex flex-col sm:flex-row gap-3 mb-6">
        <!-- Dropdown Waktu -->
        <div class="flex-1">
            <select name="waktu" 
                id="redemption-waktu-select"
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

        <!-- Dropdown Status -->
        <div class="flex-1">
            <select name="status" 
                id="redemption-status-select"
                onchange="this.form.submit()" 
                class="w-full px-3 py-2 rounded-xl border border-outline-variant/35 focus:border-primary focus:ring-2 focus:ring-primary/20 bg-surface-container-lowest text-xs transition-all cursor-pointer focus:outline-none">
                <option value="semua">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Siap Diambil</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        
        @if(request()->anyFilled(['waktu', 'status']))
            <a href="{{ route('warga.redemption') }}" class="px-3.5 py-2 bg-surface-container-low hover:bg-surface-container text-on-surface-variant text-xs font-bold rounded-xl transition-all flex items-center justify-center shrink-0">
                Clear
            </a>
        @endif
    </form>

    <!-- Redemption Table / List -->
    @if($redemptions->isEmpty())
        <div class="bg-surface-container-lowest rounded-2xl p-8 text-center border border-outline-variant/20 shadow-sm flex flex-col items-center justify-center">
            <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary mb-3 shadow-inner">
                <span class="material-symbols-outlined text-[24px] font-bold">shopping_bag</span>
            </div>
            <h3 class="text-sm font-bold text-on-surface mb-0.5">Belum Ada Riwayat Penukaran</h3>
            <p class="text-xs text-tertiary max-w-sm mb-4 leading-relaxed">
                @if(request()->anyFilled(['waktu', 'status']))
                    Tidak menemukan riwayat penukaran yang cocok dengan filter pencarian Anda.
                @else
                    Anda belum menukarkan poin dengan sembako. Tukarkan poin hasil tabungan sampah Anda sekarang.
                @endif
            </p>
            @if(request()->anyFilled(['waktu', 'status']))
                <a href="{{ route('warga.redemption') }}" class="px-4 py-2 bg-primary hover:bg-primary-container text-on-primary text-xs font-bold rounded-xl shadow transition-all flex items-center gap-1 hover:-translate-y-0.5">
                    <span class="material-symbols-outlined text-[14px]">refresh</span>
                    Reset Filter
                </a>
            @else
                <a href="{{ route('warga.katalog') }}" id="redemption-empty-cta" class="px-4 py-2 bg-primary hover:bg-primary-container text-on-primary text-xs font-bold rounded-xl shadow transition-all flex items-center gap-1 hover:-translate-y-0.5">
                    <span class="material-symbols-outlined text-[14px]">storefront</span>
                    Lihat Katalog Reward
                </a>
            @endif
        </div>
    @else
        <!-- DESKTOP TABLE VIEW -->
        <div class="hidden sm:block overflow-hidden rounded-xl border border-outline-variant/15 shadow-sm bg-white">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low text-on-surface-variant text-[11px] font-black uppercase tracking-wider border-b border-outline-variant/25">
                        <th class="px-4 py-3 w-28">ID</th>
                        <th class="px-4 py-3 w-36">Tanggal</th>
                        <th class="px-4 py-3">Item Ditukar</th>
                        <th class="px-4 py-3 w-28 text-right">Poin</th>
                        <th class="px-4 py-3 w-32 text-center">Status</th>
                        <th class="px-4 py-3 w-40 text-center">Jadwal Ambil</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @foreach($redemptions as $redemption)
                        @php
                            $badgeClass = '';
                            $statusLabel = 'Pending';
                            
                            switch($redemption->status) {
                                case 'pending': 
                                    $badgeClass = 'bg-amber-500/10 text-amber-700 border-amber-500/20'; 
                                    $statusLabel = 'Pending';
                                    break;
                                case 'approved': 
                                    $badgeClass = 'bg-blue-500/10 text-blue-700 border-blue-500/20'; 
                                    $statusLabel = 'Disetujui';
                                    break;
                                case 'ready': 
                                    $badgeClass = 'bg-emerald-500/10 text-emerald-700 border-emerald-500/20'; 
                                    $statusLabel = 'Siap Diambil';
                                    break;
                                case 'completed': 
                                    $badgeClass = 'bg-gray-500/10 text-gray-700 border-gray-500/20'; 
                                    $statusLabel = 'Selesai';
                                    break;
                                case 'rejected': 
                                    $badgeClass = 'bg-red-500/10 text-red-700 border-red-500/20'; 
                                    $statusLabel = 'Ditolak';
                                    break;
                            }
                        @endphp
                        <!-- Main Row -->
                        <tr onclick="toggleDetail('redemption-{{ $redemption->id }}')" class="hover:bg-surface-container-low/60 cursor-pointer transition-colors odd:bg-white even:bg-surface-container-lowest/30">
                            <td class="px-4 py-3 font-extrabold text-xs text-on-surface">#{{ str_pad($redemption->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-4 py-3 text-xs text-tertiary">{{ $redemption->created_at->translatedFormat('d M Y, H:i') }}</td>
                            <td class="px-4 py-3 text-xs text-on-surface-variant truncate max-w-xs font-semibold">
                                @foreach($redemption->details as $detail)
                                    {{ $detail->reward->name ?? 'Barang' }} (x{{ $detail->qty }}){{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            </td>
                            <td class="px-4 py-3 text-xs text-error font-black text-right">
                                -{{ number_format($redemption->total_point, 0, ',', '.') }} Poin
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold border {{ $badgeClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs text-on-surface font-extrabold text-center">
                                @if(in_array($redemption->status, ['approved', 'ready']))
                                    {{ $redemption->tanggal_ambil ? \Carbon\Carbon::parse($redemption->tanggal_ambil)->translatedFormat('d M Y') : 'Dipersiapkan' }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <!-- Collapsible Detail Row -->
                        <tr id="detail-redemption-{{ $redemption->id }}" class="hidden bg-surface-container-low/20">
                            <td colspan="6" class="px-6 py-4 border-t border-b border-outline-variant/10">
                                <div class="space-y-3 max-w-3xl">
                                    
                                    <!-- Items Breakdown -->
                                    <div class="bg-white rounded-lg p-3 border border-outline-variant/15">
                                        <p class="text-[10px] font-black text-tertiary uppercase tracking-wider mb-2">Daftar Item Penukaran:</p>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                            @foreach($redemption->details as $detail)
                                                <div class="flex items-center justify-between bg-surface-container-lowest px-3 py-2 rounded border border-outline-variant/10 text-xs">
                                                    <span class="font-bold text-on-surface-variant">{{ $detail->reward->name ?? 'Barang' }}</span>
                                                    <span class="font-black text-on-surface">x{{ $detail->qty }} <span class="text-[10px] text-tertiary font-medium">({{ number_format($detail->subtotal_point, 0, ',', '.') }} pts)</span></span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Rejection Catatan Admin -->
                                    @if($redemption->status === 'rejected' && $redemption->catatan_admin)
                                        <div class="bg-red-50 border border-red-150 p-3 rounded-lg text-xs text-red-800 flex items-start gap-2">
                                            <span class="material-symbols-outlined text-[16px] text-error font-bold mt-0.5">info</span>
                                            <div>
                                                <span class="font-bold">Alasan Penolakan:</span>
                                                <p class="mt-0.5">{{ $redemption->catatan_admin }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Timeline Compact -->
                                    @if($redemption->status !== 'rejected')
                                        @php
                                            $statuses = ['pending', 'approved', 'ready', 'completed'];
                                            $currentIndex = array_search($redemption->status, $statuses);
                                        @endphp
                                        <div class="bg-white rounded-lg p-3 border border-outline-variant/15 flex items-center justify-between gap-4">
                                            <span class="text-[9px] font-black text-tertiary uppercase tracking-wider">Progress Alur:</span>
                                            <div class="flex items-center gap-2">
                                                @foreach([
                                                    ['label' => 'Diajukan'],
                                                    ['label' => 'Disetujui'],
                                                    ['label' => 'Siap'],
                                                    ['label' => 'Selesai']
                                                ] as $idx => $step)
                                                    <span class="flex items-center gap-1.5">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $idx <= $currentIndex ? 'bg-primary' : 'bg-surface-container-high border border-outline-variant/50' }}"></span>
                                                        <span class="text-[9px] font-bold {{ $idx <= $currentIndex ? 'text-primary' : 'text-tertiary/60' }}">{{ $step['label'] }}</span>
                                                    </span>
                                                    @if(!$loop->last)
                                                        <span class="h-px w-3 bg-outline-variant/30"></span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- MOBILE LIST VIEW -->
        <div class="block sm:hidden space-y-2">
            @foreach($redemptions as $redemption)
                @php
                    $circleClass = '';
                    $badgeClass = '';
                    $statusLabel = 'Pending';
                    
                    switch($redemption->status) {
                        case 'pending': 
                            $circleClass = 'bg-amber-50 text-amber-600 border border-amber-100';
                            $badgeClass = 'bg-amber-500/10 text-amber-700 border border-amber-500/20'; 
                            $statusLabel = 'Pending';
                            break;
                        case 'approved': 
                            $circleClass = 'bg-blue-50 text-blue-600 border border-blue-100';
                            $badgeClass = 'bg-blue-500/10 text-blue-700 border border-blue-500/20'; 
                            $statusLabel = 'Disetujui';
                            break;
                        case 'ready': 
                            $circleClass = 'bg-emerald-50 text-emerald-600 border border-emerald-100';
                            $badgeClass = 'bg-emerald-500/10 text-emerald-700 border border-emerald-500/20'; 
                            $statusLabel = 'Siap Diambil';
                            break;
                        case 'completed': 
                            $circleClass = 'bg-gray-100 text-gray-600 border border-gray-200';
                            $badgeClass = 'bg-gray-500/10 text-gray-700 border border-gray-500/20'; 
                            $statusLabel = 'Selesai';
                            break;
                        case 'rejected': 
                            $circleClass = 'bg-red-50 text-red-600 border border-red-100';
                            $badgeClass = 'bg-red-500/10 text-red-700 border border-red-500/20'; 
                            $statusLabel = 'Ditolak';
                            break;
                    }
                @endphp
                <div onclick="toggleDetail('redemption-{{ $redemption->id }}')" class="bg-surface-container-lowest p-3.5 rounded-xl border border-outline-variant/15 hover:border-primary/20 shadow-sm cursor-pointer odd:bg-white even:bg-surface-container-lowest/30">
                    <div class="flex justify-between items-center mb-1">
                        <div class="flex items-center gap-1.5">
                            <span class="font-bold text-xs text-on-surface">Penukaran #{{ str_pad($redemption->id, 5, '0', STR_PAD_LEFT) }}</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[8px] font-black border {{ $badgeClass }}">
                                {{ $statusLabel }}
                            </span>
                        </div>
                        <span class="text-xs font-black text-error">-{{ number_format($redemption->total_point, 0, ',', '.') }} Poin</span>
                    </div>
                    
                    <div class="flex justify-between items-center text-[10px] text-tertiary">
                        <span>{{ $redemption->created_at->translatedFormat('d M Y, H:i') }}</span>
                        @if(in_array($redemption->status, ['approved', 'ready']))
                            <span class="text-emerald-700 font-bold">Jadwal: {{ $redemption->tanggal_ambil ? \Carbon\Carbon::parse($redemption->tanggal_ambil)->translatedFormat('d/m/y') : 'Dipersiapkan' }}</span>
                        @endif
                    </div>
                    
                    <!-- Collapsible mobile details -->
                    <div id="mobile-detail-redemption-{{ $redemption->id }}" class="hidden mt-3 pt-3 border-t border-outline-variant/10 text-xs text-on-surface-variant space-y-2 animate-fade-in-up">
                        <p class="text-[9px] font-black text-tertiary uppercase tracking-wider">Item Penukaran:</p>
                        @foreach($redemption->details as $detail)
                            <div class="flex justify-between items-center bg-white px-2.5 py-1.5 rounded border border-outline-variant/10 text-[11px]">
                                <span class="font-medium text-on-surface-variant">{{ $detail->reward->name ?? 'Barang' }} (x{{ $detail->qty }})</span>
                                <span class="font-extrabold text-tertiary">{{ number_format($detail->subtotal_point, 0, ',', '.') }} pts</span>
                            </div>
                        @endforeach
                        
                        @if($redemption->status === 'rejected' && $redemption->catatan_admin)
                            <div class="bg-red-50 border border-red-150 p-2.5 rounded text-[11px] text-red-800">
                                <strong>Alasan Ditolak:</strong> {{ $redemption->catatan_admin }}
                            </div>
                        @endif
                        
                        <!-- Mini mobile timeline -->
                        @if($redemption->status !== 'rejected')
                            @php
                                $statuses = ['pending', 'approved', 'ready', 'completed'];
                                $currentIndex = array_search($redemption->status, $statuses);
                            @endphp
                            <div class="flex items-center gap-1.5 justify-center pt-2 border-t border-outline-variant/5">
                                @foreach(['Diajukan', 'Disetujui', 'Siap', 'Selesai'] as $idx => $lbl)
                                    <span class="flex items-center gap-1">
                                        <span class="w-1 h-1 rounded-full {{ $idx <= $currentIndex ? 'bg-primary' : 'bg-surface-container-high' }}"></span>
                                        <span class="text-[8px] font-bold {{ $idx <= $currentIndex ? 'text-primary' : 'text-tertiary/60' }}">{{ $lbl }}</span>
                                    </span>
                                    @if(!$loop->last)
                                        <span class="h-px w-2 bg-outline-variant/20"></span>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if($redemptions->hasPages())
            <div class="mt-6">
                {{ $redemptions->links() }}
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
