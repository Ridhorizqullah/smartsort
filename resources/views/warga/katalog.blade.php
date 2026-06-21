@extends('layouts.app')

@section('title', 'Katalog Sembako - SmartSort')

@section('content')
<section class="mb-stack-lg animate-fade-in-up">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-stack-md">
        <div>
            <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">Katalog Sembako</h1>
            <p class="font-body-md text-tertiary">Tukarkan poin Anda dengan bahan pokok kebutuhan sehari-hari.</p>
        </div>
        <!-- Poin Tersedia -->
        <div class="bg-primary-container/20 text-on-surface px-4 py-2 rounded-lg border border-primary/20 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">account_balance_wallet</span>
            <span class="font-label-md">Poin Anda: <strong>{{ number_format(auth()->user()->saldo_poin, 0, ',', '.') }}</strong></span>
        </div>
    </div>

    @if($rewards->count() == 0)
        <div class="bg-surface-container-lowest rounded-xl p-8 text-center text-on-surface-variant border border-outline-variant/30">
            Katalog sembako sedang kosong. Silakan periksa kembali nanti.
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($rewards as $reward)
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-sm overflow-hidden hover:shadow-md transition-shadow group flex flex-col">
                <!-- Image Placeholder -->
                <div class="aspect-4/3 bg-surface-variant relative overflow-hidden flex items-center justify-center">
                    <span class="material-symbols-outlined text-[64px] text-tertiary opacity-50">shopping_basket</span>
                    
                    @if($reward->stock < 10)
                        <div class="absolute top-3 right-3 bg-error text-on-error text-[10px] font-bold px-2 py-1 rounded shadow-sm">
                            Sisa {{ $reward->stock }}
                        </div>
                    @else
                        <div class="absolute top-3 right-3 bg-primary text-on-primary text-[10px] font-bold px-2 py-1 rounded shadow-sm">
                            Stok: {{ $reward->stock }}
                        </div>
                    @endif
                </div>
                
                <div class="p-5 flex flex-col flex-1">
                    <h3 class="font-headline-md text-[18px] text-on-surface mb-1 group-hover:text-primary transition-colors line-clamp-2">
                        {{ $reward->name }}
                    </h3>
                    <p class="text-sm text-tertiary mb-4 flex-1 line-clamp-3">
                        {{ $reward->description ?? 'Paket sembako berkualitas untuk kebutuhan rumah tangga.' }}
                    </p>
                    
                    <div class="flex justify-between items-center mt-auto pt-4 border-t border-outline-variant/20">
                        <div class="font-headline-md text-primary font-bold">
                            {{ number_format($reward->point_cost, 0, ',', '.') }} Poin
                        </div>
                        <form method="POST" action="{{ route('warga.redemption.store') }}" class="flex items-center gap-2">
                            @csrf
                            <input type="hidden" name="reward_id" value="{{ $reward->id }}">
                            <input type="hidden" name="idempotency_key" value="{{ Str::uuid() }}">
                            <input type="number" name="qty" value="1" min="1" max="{{ $reward->stock }}" class="w-16 px-2 py-1 border border-outline-variant rounded-lg text-center" required>
                            
                            <button type="submit" class="bg-primary/10 text-primary hover:bg-primary hover:text-on-primary p-2 rounded-lg transition-colors" title="Tukar Poin">
                                <span class="material-symbols-outlined">add_shopping_cart</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    @if($rewards->hasPages())
        <div class="mt-8">
            {{ $rewards->links() }}
        </div>
    @endif
</section>

@section('scripts')
<script>
// Proteksi double-submit: disable tombol setelah form disubmit
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form[action*="penukaran"]').forEach(function (form) {
        form.addEventListener('submit', function () {
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        });
    });
});
</script>
@endsection
