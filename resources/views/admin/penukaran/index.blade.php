@extends('layouts.admin')

@section('title', 'Approval Penukaran - SmartSort')
@section('header', 'Persetujuan Penukaran')

@section('content')
<div class="bg-white rounded-2xl border border-outline-variant/30 shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-outline-variant/30 bg-surface-container-lowest/50 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-bold text-on-surface">Antrean Penukaran Sembako</h2>
            <p class="text-xs text-on-surface-variant mt-0.5">Tinjau, setujui, atau tolak permohonan penukaran barang dari warga.</p>
        </div>
        <div class="bg-primary/10 text-primary px-3 py-1.5 rounded-xl text-xs font-bold flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">schedule</span>
            {{ $redemptions->total() }} Tiket Masuk
        </div>
    </div>

    <!-- Filter & Search Bar -->
<div class="px-6 py-4 border-b border-outline-variant/30 bg-surface-container-lowest">

    <!-- ✅ ROW 1: SEARCH (CENTER) -->
    <div class="flex justify-center mb-4">
        <div class="relative w-full md:w-[500px] lg:w-[600px]">

            <span class="material-symbols-outlined 
                absolute left-3.5 top-1/2 -translate-y-1/2 text-[20px] z-10 pointer-events-none">
                search
            </span>

            <input type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari Nama / NIK / ID Tiket..."
                class="w-full pr-4 py-2 bg-white border rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                style="padding-left: 2.75rem;">

        </div>
    </div>

        <!-- ✅ ROW 2: FILTER + EXPORT -->
        <form method="GET" action="{{ route('admin.penukaran') }}" 
            class="flex flex-wrap items-center justify-between gap-3">

            <div class="flex flex-wrap items-center gap-3">

                <!-- Time Filter -->
                <select name="time_filter" class="px-3 py-2 border rounded-xl text-sm">
                    <option value="">Semua Waktu</option>
                    <option value="hari_ini">Hari Ini</option>
                    <option value="minggu_ini">Minggu Ini</option>
                    <option value="bulan_ini">Bulan Ini</option>
                    <option value="bulan_lalu">Bulan Lalu</option>
                    <option value="tahun_ini">Tahun Ini</option>
                </select>

                <!-- Status Filter -->
                <select name="status_filter" class="px-3 py-2 border rounded-xl text-sm">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="ready">Ready</option>
                    <option value="completed">Completed</option>
                    <option value="rejected">Rejected</option>
                </select>

                <!-- Filter Button -->
                <button class="px-4 py-2 bg-primary text-white rounded-xl text-sm font-bold">
                    Filter
                </button>

            </div>

            <!-- ✅ EXPORT -->
            <button type="submit"
                formaction="{{ route('admin.penukaran.export') }}"
                class="px-4 py-2 bg-primary hover:bg-primary/80 text-white rounded-xl text-sm font-bold flex items-center gap-1">
                
                <span class="material-symbols-outlined text-sm">download</span>
                Export CSV
            </button>

        </form>

    </div>

    
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-surface-container-low text-on-surface-variant font-bold border-b border-outline-variant/30">
                <tr>
                    <th class="px-6 py-4">ID Tiket / Tanggal</th>
                    <th class="px-6 py-4">Nama Warga</th>
                    <th class="px-6 py-4">Kebutuhan Sembako</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-right">Potongan Saldo</th>
                    <th class="px-6 py-4 text-center">Aksi Persetujuan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/20">
                @forelse($redemptions as $redemption)
                <tr class="hover:bg-surface-container-lowest transition-colors">
                    <td class="px-6 py-4">
                        <span class="font-mono text-xs font-bold text-primary">#{{ substr($redemption->idempotency_key ?? $redemption->id, 0, 8) }}</span>
                        <div class="text-[11px] text-on-surface-variant mt-1 flex items-center gap-1 font-semibold">
                            <span class="material-symbols-outlined text-xs">calendar_today</span>
                            {{ $redemption->created_at->format('d M Y, H:i') }} WIB
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-on-surface">{{ $redemption->user->name ?? 'Warga Tidak Ditemukan' }}</div>
                        <div class="text-xs text-on-surface-variant/80 mt-0.5 flex items-center gap-1 font-semibold">
                            <span class="material-symbols-outlined text-xs">badge</span>
                            NIK: {{ $redemption->user->nik ?? '-' }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="space-y-1">
                            @foreach($redemption->details as $detail)
                                <div class="inline-flex items-center gap-1.5 bg-surface-container/60 px-2.5 py-1 rounded-lg text-xs font-semibold text-on-surface-variant border border-outline-variant/25">
                                    <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                                    {{ $detail->qty }}x {{ $detail->reward->name ?? 'Reward' }}
                                </div>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($redemption->status == 'pending')
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-yellow-50 text-yellow-800 border border-yellow-200 text-xs font-bold">
                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span>
                                PENDING
                            </span>
                        @elseif($redemption->status == 'approved')
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-blue-50 text-blue-800 border border-blue-200 text-xs font-bold">
                                <span class="material-symbols-outlined text-xs">done</span>
                                APPROVED
                            </span>
                        @elseif($redemption->status == 'ready')
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-50 text-green-800 border border-green-200 text-xs font-bold">
                                <span class="material-symbols-outlined text-xs">inventory_2</span>
                                READY
                            </span>
                        @elseif($redemption->status == 'completed')
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-gray-50 text-gray-600 border border-gray-200 text-xs font-bold">
                                <span class="material-symbols-outlined text-xs">task_alt</span>
                                COMPLETED
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-red-50 text-red-800 border border-red-200 text-xs font-bold">
                                <span class="material-symbols-outlined text-xs">cancel</span>
                                REJECTED
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <span class="font-extrabold text-error">-{{ number_format($redemption->total_point) }} Poin</span>
                        <div class="text-[10px] text-on-surface-variant font-semibold mt-0.5">Saldo: {{ number_format($redemption->user->saldo_poin ?? 0) }} Poin</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($redemption->status == 'pending')
                            <div class="flex items-center justify-center gap-2">
                                <!-- Tombol Setuju (Panggil Modal) -->
                                <button type="button" 
                                    onclick="openApproveModal({{ $redemption->id }}, '{{ route('admin.penukaran.approve', $redemption->id) }}', '{{ $redemption->user->name }}', {{ $redemption->total_point }}, {{ $redemption->user->saldo_poin ?? 0 }})" 
                                    class="h-9 px-3 bg-primary hover:bg-primary-container text-on-primary rounded-xl font-bold flex items-center justify-center gap-1 transition-all shadow-sm text-xs hover:-translate-y-0.5 active:scale-95">
                                    <span class="material-symbols-outlined text-sm">check</span>
                                    Setujui
                                </button>

                                <!-- Tombol Tolak (Panggil Modal) -->
                                <button type="button" 
                                    onclick="openRejectModal({{ $redemption->id }}, '{{ route('admin.penukaran.reject', $redemption->id) }}', '{{ $redemption->user->name }}')" 
                                    class="h-9 px-3 bg-red-50 hover:bg-red-100 text-error rounded-xl font-bold flex items-center justify-center gap-1 transition-all text-xs hover:-translate-y-0.5 active:scale-95">
                                    <span class="material-symbols-outlined text-sm">close</span>
                                    Tolak
                                </button>
                            </div>
                        @else
                            <div class="text-center text-xs text-on-surface-variant font-semibold">
                                @if($redemption->status == 'rejected')
                                    <span class="text-error" title="Alasan: {{ $redemption->catatan_admin ?? 'Tidak ada catatan' }}">Ditolak</span>
                                @elseif($redemption->status == 'approved')
                                    <span class="text-primary">Disetujui</span>
                                @else
                                    <span class="text-on-surface-variant capitalize">{{ $redemption->status }}</span>
                                @endif
                            </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-on-surface-variant font-medium">
                        <span class="material-symbols-outlined text-5xl text-on-surface-variant/40 mb-3">inbox</span>
                        <p class="text-sm">Tidak ada antrean permohonan penukaran saat ini.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($redemptions->hasPages())
    <div class="p-6 border-t border-outline-variant/30 bg-surface-container-lowest/50">
        {{ $redemptions->links() }}
    </div>
    @endif
</div>

<!-- Modal Approval (Setujui) -->
<div id="approveModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-300">
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="closeApproveModal()"></div>
    <div class="relative bg-white rounded-2xl border border-outline-variant/30 shadow-xl max-w-md w-full overflow-hidden transition-all transform translate-y-4 duration-300" id="approveModalContainer">
        <form id="approveForm" method="POST">
            @csrf
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">check_circle</span>
                        Konfirmasi Persetujuan
                    </h3>
                    <button type="button" class="p-1.5 hover:bg-surface-container rounded-full text-on-surface-variant transition-colors" onclick="closeApproveModal()">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                </div>
                
                <div class="bg-primary/5 p-4 rounded-xl border border-primary/10 text-xs space-y-2 mb-6">
                    <div class="flex justify-between">
                        <span class="text-on-surface-variant font-semibold">Nama Warga:</span>
                        <span class="font-bold text-on-surface" id="approveWargaName">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-on-surface-variant font-semibold">Poin Dibutuhkan:</span>
                        <span class="font-bold text-error" id="approvePointCost">- Poin</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-on-surface-variant font-semibold">Saldo Poin Warga:</span>
                        <span class="font-bold text-primary" id="approveWargaSaldo">- Poin</span>
                    </div>
                </div>

                <!-- Input Tanggal Ambil -->
                <div class="mb-6">
                    <label class="block text-xs font-bold text-on-surface-variant mb-2">Tanggal Pengambilan Barang</label>
                    <input type="date" name="tanggal_ambil" required id="tanggal_ambil_input" class="w-full border border-outline-variant/50 rounded-xl px-4 py-2.5 bg-white text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-on-surface-variant rounded-xl text-xs font-bold transition-all" onclick="closeApproveModal()">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-primary hover:bg-primary-container text-on-primary rounded-xl text-xs font-bold transition-all shadow-sm">
                        Ya, Setujui & Potong Poin
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Rejection (Tolak) -->
<div id="rejectModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-300">
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="closeRejectModal()"></div>
    <div class="relative bg-white rounded-2xl border border-outline-variant/30 shadow-xl max-w-md w-full overflow-hidden transition-all transform translate-y-4 duration-300" id="rejectModalContainer">
        <form id="rejectForm" method="POST">
            @csrf
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-error">cancel</span>
                        Tolak Permohonan
                    </h3>
                    <button type="button" class="p-1.5 hover:bg-surface-container rounded-full text-on-surface-variant transition-colors" onclick="closeRejectModal()">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                </div>

                <div class="mb-4">
                    <p class="text-xs text-on-surface-variant">Tolak permohonan penukaran barang dari warga bernama <strong id="rejectWargaName">-</strong>.</p>
                </div>

                <!-- Input Catatan/Alasan Tolak -->
                <div class="mb-6">
                    <label class="block text-xs font-bold text-on-surface-variant mb-2">Alasan Penolakan</label>
                    <textarea name="catatan" required rows="3" placeholder="Contoh: Saldo poin tidak mencukupi atau stok barang kosong..." class="w-full border border-outline-variant/50 rounded-xl px-4 py-2.5 bg-white text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all resize-none"></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-on-surface-variant rounded-xl text-xs font-bold transition-all" onclick="closeRejectModal()">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-error hover:bg-red-700 text-on-error rounded-xl text-xs font-bold transition-all shadow-sm">
                        Ya, Tolak Permohonan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Set default tanggal ambil = hari ini
    document.getElementById('tanggal_ambil_input').value = new Date().toISOString().slice(0, 10);

    // Modal Approval
    function openApproveModal(id, actionUrl, wargaName, totalPoint, wargaSaldo) {
        document.getElementById('approveForm').action = actionUrl;
        document.getElementById('approveWargaName').textContent = wargaName;
        document.getElementById('approvePointCost').textContent = new Intl.NumberFormat('id-ID').format(totalPoint) + ' Poin';
        document.getElementById('approveWargaSaldo').textContent = new Intl.NumberFormat('id-ID').format(wargaSaldo) + ' Poin';
        
        const modal = document.getElementById('approveModal');
        const container = document.getElementById('approveModalContainer');
        
        modal.classList.remove('opacity-0', 'pointer-events-none');
        setTimeout(() => {
            container.classList.remove('translate-y-4');
        }, 10);
    }

    function closeApproveModal() {
        const modal = document.getElementById('approveModal');
        const container = document.getElementById('approveModalContainer');
        
        container.classList.add('translate-y-4');
        setTimeout(() => {
            modal.classList.add('opacity-0', 'pointer-events-none');
        }, 150);
    }

    // Modal Rejection
    function openRejectModal(id, actionUrl, wargaName) {
        document.getElementById('rejectForm').action = actionUrl;
        document.getElementById('rejectWargaName').textContent = wargaName;
        
        const modal = document.getElementById('rejectModal');
        const container = document.getElementById('rejectModalContainer');
        
        modal.classList.remove('opacity-0', 'pointer-events-none');
        setTimeout(() => {
            container.classList.remove('translate-y-4');
        }, 10);
    }

    function closeRejectModal() {
        const modal = document.getElementById('rejectModal');
        const container = document.getElementById('rejectModalContainer');
        
        container.classList.add('translate-y-4');
        setTimeout(() => {
            modal.classList.add('opacity-0', 'pointer-events-none');
        }, 150);
    }
</script>
@endpush
