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
    <div class="premium-gradient rounded-xl p-8 text-on-primary relative overflow-hidden shadow-lg border border-white/20">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-secondary-container/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 -left-12 w-48 h-48 bg-primary-container/20 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-end gap-stack-lg">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-stack-sm">
                    <span class="font-label-md text-label-md opacity-90">Saldo Anda</span>
                    <span class="material-symbols-outlined text-[20px] opacity-80" style="font-variation-settings: 'FILL' 1;">eco</span>
                </div>
                <div class="font-headline-lg text-display-lg mb-stack-sm tracking-tight drop-shadow-sm">{{ number_format($user->saldo_poin, 0, ',', '.') }} Poin</div>
                <div class="font-body-md text-body-md opacity-90 bg-black/10 inline-block px-4 py-1.5 rounded-full backdrop-blur-sm border border-white/10">
                    Setara dengan <span class="font-bold">Rp{{ number_format($user->saldo_poin, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="w-full md:w-auto">
                <button class="w-full md:w-auto bg-secondary-container text-on-secondary-container hover:bg-[#ffdf9a] font-label-md text-label-md px-8 py-4 rounded-xl flex items-center justify-center gap-3 transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 group" onclick="document.getElementById('qr-modal').classList.remove('opacity-0', 'pointer-events-none')">
                    <span class="material-symbols-outlined text-[24px] group-hover:scale-110 transition-transform">qr_code_scanner</span>
                    Tampilkan QR Code Kartu
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Stats Cards -->
<section class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
    <div class="bg-surface-container-lowest rounded-xl p-8 flex flex-col justify-between group hover:shadow-lg transition-all border border-outline-variant/30 shadow-sm">
        <div class="flex items-start justify-between mb-stack-md">
            <div class="p-3 bg-primary/10 rounded-lg text-primary">
                <span class="material-symbols-outlined">recycling</span>
            </div>
            <span class="font-label-sm text-label-sm text-primary bg-primary/10 px-3 py-1 rounded-full font-bold">Total: {{ $totalTransaksi }} Kali</span>
        </div>
        <div>
            <h3 class="font-label-md text-label-md text-on-surface-variant mb-1">Total Disetor (Berat)</h3>
            <div class="font-headline-md text-headline-md text-on-surface">{{ number_format($totalSampah, 1, ',', '.') }} kg</div>
        </div>
    </div>
    
    <div class="bg-surface-container-lowest rounded-xl p-8 flex flex-col justify-between group hover:shadow-lg transition-all border border-outline-variant/30 shadow-sm">
        <div class="flex items-start justify-between mb-stack-md">
            <div class="p-3 bg-secondary/10 rounded-lg text-secondary">
                <span class="material-symbols-outlined">shopping_bag</span>
            </div>
        </div>
        <div>
            <h3 class="font-label-md text-label-md text-on-surface-variant mb-1">Total Penukaran (Redemption)</h3>
            <div class="font-headline-md text-headline-md text-on-surface">{{ $totalRedemption }} Tiket</div>
        </div>
    </div>
</section>

<!-- QR Modal -->
<div class="fixed inset-0 z-100 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300" id="qr-modal">
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="document.getElementById('qr-modal').classList.add('opacity-0', 'pointer-events-none')"></div>
    <div class="relative bg-surface-container-lowest rounded-xl shadow-xl max-w-sm w-full overflow-hidden animate-fade-in-up">
        <div class="p-8 flex flex-col items-center gap-6">
            <div class="flex justify-between items-center w-full">
                <h3 class="font-headline-md text-headline-md text-on-surface">Kartu Digital</h3>
                <button class="p-2 hover:bg-surface-container rounded-full transition-colors" onclick="document.getElementById('qr-modal').classList.add('opacity-0', 'pointer-events-none')">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="w-full aspect-square rounded-xl overflow-hidden border border-outline-variant/30 shadow-inner bg-white flex items-center justify-center">
                <!-- Gunakan NIK sebagai nilai QR Code dengan API -->
                <img alt="Citizen Waste Bank Card QR Code" class="w-full h-full object-contain" src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ $user->nik }}"/>
            </div>
            <p class="text-center text-on-surface-variant font-body-md">Tunjukkan QR Code ini kepada petugas bank sampah (NIK: {{ $user->nik }}).</p>
            <button class="w-full bg-primary text-on-primary py-4 rounded-lg font-label-md hover:shadow-md transition-all active:scale-95" onclick="document.getElementById('qr-modal').classList.add('opacity-0', 'pointer-events-none')">
                Tutup
            </button>
        </div>
    </div>
</div>
@endsection
