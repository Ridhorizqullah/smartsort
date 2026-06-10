@extends('layouts.app')

@section('title', 'Riwayat Setor Sampah - SmartSort')

@section('content')
<section class="mb-stack-lg animate-fade-in-up">
    <div class="flex flex-col gap-2 mb-stack-md">
        <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">Riwayat Setoran</h1>
        <p class="font-body-md text-tertiary">Pantau semua aktivitas setor sampah dan perolehan poin Anda.</p>
    </div>

    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-sm overflow-hidden">
        @if($transactions->isEmpty())
            <div class="p-8 text-center text-on-surface-variant font-body-md">
                Belum ada riwayat setor sampah.
            </div>
        @else
            <div class="divide-y divide-outline-variant/20">
                @foreach($transactions as $trx)
                <div class="p-6 hover:bg-surface-container/50 transition-colors flex flex-col md:flex-row justify-between items-start gap-4">
                    <div class="flex items-start gap-4 flex-1">
                        <div class="p-3 bg-primary/10 rounded-lg text-primary shrink-0">
                            <span class="material-symbols-outlined">recycling</span>
                        </div>
                        <div class="w-full">
                            <div class="flex flex-wrap justify-between items-center gap-2 mb-1 w-full">
                                <span class="font-label-md text-label-md text-on-surface">
                                    Setoran #{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}
                                </span>
                            </div>
                            <div class="font-label-sm text-tertiary mb-3">
                                {{ $trx->created_at->translatedFormat('d M Y, H:i') }}
                            </div>
                            
                            <!-- Rincian Kategori Sampah -->
                            <div class="bg-surface-container-low rounded-lg p-3">
                                <h4 class="text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Rincian:</h4>
                                <ul class="text-sm space-y-1">
                                    @foreach($trx->details as $detail)
                                        <li class="flex justify-between border-b border-outline-variant/10 pb-1 last:border-0 last:pb-0">
                                            <span>{{ $detail->category->name ?? 'Kategori' }} ({{ $detail->weight }} kg)</span>
                                            <span class="font-semibold">+{{ number_format($detail->subtotal_point, 0, ',', '.') }} Poin</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-right shrink-0 mt-4 md:mt-0">
                        <div class="font-headline-md text-primary font-bold">
                            +{{ number_format($trx->total_point, 0, ',', '.') }} Poin
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            @if($transactions->hasPages())
                <div class="p-4 border-t border-outline-variant/20">
                    {{ $transactions->links() }}
                </div>
            @endif
        @endif
    </div>
</section>
@endsection
