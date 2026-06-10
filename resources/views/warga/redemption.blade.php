@extends('layouts.app')

@section('title', 'Riwayat Penukaran - SmartSort')

@section('content')
<section class="mb-stack-lg animate-fade-in-up">
    <div class="flex flex-col gap-2 mb-stack-md">
        <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">Riwayat Penukaran</h1>
        <p class="font-body-md text-tertiary">Pantau status penukaran poin Anda dengan paket sembako.</p>
    </div>

    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-sm overflow-hidden">
        @if($redemptions->isEmpty())
            <div class="p-8 text-center text-on-surface-variant font-body-md">
                Belum ada riwayat penukaran poin.
            </div>
        @else
            <div class="divide-y divide-outline-variant/20">
                @foreach($redemptions as $redemption)
                <div class="p-6 hover:bg-surface-container/50 transition-colors flex flex-col md:flex-row justify-between items-start gap-4">
                    <div class="flex items-start gap-4 flex-1">
                        <div class="p-3 bg-secondary/10 rounded-lg text-secondary shrink-0">
                            <span class="material-symbols-outlined">shopping_bag</span>
                        </div>
                        <div class="w-full">
                            <div class="flex flex-wrap justify-between items-center gap-2 mb-1 w-full">
                                <span class="font-label-md text-label-md text-on-surface">
                                    Penukaran #{{ str_pad($redemption->id, 5, '0', STR_PAD_LEFT) }}
                                </span>
                                
                                <!-- Implementasi Badge Status sesuai Rule -->
                                @php
                                    $badgeClass = '';
                                    $statusLabel = ucfirst($redemption->status);
                                    switch($redemption->status) {
                                        case 'pending': $badgeClass = 'badge-pending'; break;
                                        case 'approved': $badgeClass = 'badge-approved'; break;
                                        case 'ready': $badgeClass = 'badge-ready'; break;
                                        case 'completed': $badgeClass = 'badge-completed'; break;
                                        case 'rejected': $badgeClass = 'badge-rejected'; break;
                                    }
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                            <div class="font-label-sm text-tertiary mb-3">
                                {{ $redemption->created_at->translatedFormat('d M Y, H:i') }}
                            </div>
                            
                            <!-- Rincian Barang -->
                            <div class="bg-surface-container-low rounded-lg p-3">
                                <h4 class="text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Item Ditukar:</h4>
                                <ul class="text-sm space-y-1">
                                    @foreach($redemption->details as $detail)
                                        <li class="flex justify-between border-b border-outline-variant/10 pb-1 last:border-0 last:pb-0">
                                            <span>{{ $detail->reward->name ?? 'Barang' }} (x{{ $detail->qty }})</span>
                                            <span class="font-semibold">{{ number_format($detail->subtotal_point, 0, ',', '.') }} Poin</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            
                            <!-- Catatan Penolakan jika ada -->
                            @if($redemption->status === 'rejected' && $redemption->catatan_admin)
                                <div class="mt-3 p-3 bg-error/10 text-error text-sm rounded-lg border border-error/20">
                                    <strong>Alasan Ditolak:</strong> {{ $redemption->catatan_admin }}
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="text-right shrink-0 mt-4 md:mt-0">
                        <div class="font-headline-md text-error font-bold">
                            -{{ number_format($redemption->total_point, 0, ',', '.') }} Poin
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
        
        @if(isset($redemptions) && $redemptions->hasPages())
            <div class="p-4 border-t border-outline-variant/20 bg-surface-container-lowest">
                {{ $redemptions->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
