@extends('layouts.app')

@section('title', 'Katalog Sembako - SmartSort')

@section('content')
<section class="mb-stack-lg animate-fade-in-up">
    <!-- Header Page -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-stack-md">
        <div>
            <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">Katalog Sembako</h1>
            <p class="font-body-md text-tertiary">Tukarkan poin hasil tabungan sampah Anda dengan bahan pokok berkualitas.</p>
        </div>
        <!-- Poin Tersedia -->
        <div class="bg-primary-container/15 text-primary px-4 py-3 rounded-2xl border border-primary/20 flex items-center gap-2.5 shadow-sm">
            <span class="material-symbols-outlined text-[20px] font-bold">account_balance_wallet</span>
            <span class="font-label-md text-sm">Poin Tersedia: <strong class="text-primary font-extrabold text-[15px] ml-0.5">{{ number_format(auth()->user()->saldo_poin, 0, ',', '.') }} pts</strong></span>
        </div>
    </div>

    <!-- Alert Notifikasi Session -->
    @if(session('success'))
        <div class="mb-6 flex gap-3 items-center bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm animate-fade-in-up">
            <span class="material-symbols-outlined text-[20px]">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 flex gap-3 items-center bg-red-50 border border-red-200 text-error px-4 py-3 rounded-xl text-sm animate-fade-in-up">
            <span class="material-symbols-outlined text-[20px]">error</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($rewards->count() == 0)
        <div class="bg-surface-container-lowest rounded-2xl p-12 text-center text-on-surface-variant border border-outline-variant/30 shadow-sm">
            <span class="material-symbols-outlined text-5xl text-outline-variant mb-3">inbox</span>
            <p class="font-body-md">Katalog reward sedang kosong atau stok habis. Silakan periksa kembali nanti.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($rewards as $reward)
                @php
                    $rewardNameLower = strtolower($reward->name);
                    $iconName = 'shopping_bag'; // default
                    
                    if (str_contains($rewardNameLower, 'beras')) {
                        $iconName = 'rice_bowl';
                    } elseif (str_contains($rewardNameLower, 'minyak')) {
                        $iconName = 'oil_barrel';
                    } elseif (str_contains($rewardNameLower, 'gula')) {
                        $iconName = 'grain';
                    } elseif (str_contains($rewardNameLower, 'tepung')) {
                        $iconName = 'bakery_dining';
                    } elseif (str_contains($rewardNameLower, 'mie')) {
                        $iconName = 'ramen_dining';
                    } elseif (str_contains($rewardNameLower, 'sabun') || str_contains($rewardNameLower, 'cuci') || str_contains($rewardNameLower, 'deterjen')) {
                        $iconName = 'soap';
                    } elseif (str_contains($rewardNameLower, 'kecap') || str_contains($rewardNameLower, 'saus')) {
                        $iconName = 'restaurant';
                    } elseif (str_contains($rewardNameLower, 'sarden') || str_contains($rewardNameLower, 'ikan')) {
                        $iconName = 'set_meal';
                    } elseif (str_contains($rewardNameLower, 'teh') || str_contains($rewardNameLower, 'kopi')) {
                        $iconName = 'emoji_food_beverage';
                    }
                @endphp
                <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 shadow-sm overflow-hidden hover:shadow-md transition-all group flex flex-col hover:-translate-y-0.5 duration-200">
                    
                    <!-- Reward Image / Illustration Header -->
                    <div class="aspect-4/3 bg-linear-to-tr from-primary/5 to-primary-container/10 relative overflow-hidden flex items-center justify-center border-b border-outline-variant/10">
                        <span class="material-symbols-outlined text-[72px] text-primary/30 group-hover:scale-110 group-hover:text-primary/45 transition-all duration-300 select-none">
                            {{ $iconName }}
                        </span>
                        
                        <!-- Adaptative Stock Badge -->
                        @if($reward->stock >= 10)
                            <div class="absolute top-3 right-3 bg-emerald-500 text-white text-[10px] font-extrabold px-2.5 py-1.5 rounded-lg shadow-sm tracking-wider uppercase">
                                Stok 10+
                            </div>
                        @elseif($reward->stock > 0)
                            <div class="absolute top-3 right-3 bg-amber-500 text-white text-[10px] font-extrabold px-2.5 py-1.5 rounded-lg shadow-sm tracking-wider uppercase">
                                Sisa {{ $reward->stock }}
                            </div>
                        @else
                            <div class="absolute top-3 right-3 bg-red-500 text-white text-[10px] font-extrabold px-2.5 py-1.5 rounded-lg shadow-sm tracking-wider uppercase">
                                Habis
                            </div>
                        @endif
                    </div>
                    
                    <!-- Card Contents -->
                    <div class="p-5 flex flex-col flex-1">
                        <h3 class="font-headline-md text-[18px] text-on-surface mb-1 group-hover:text-primary transition-colors line-clamp-2">
                            {{ $reward->name }}
                        </h3>
                        <p class="text-xs text-tertiary mb-3 flex-1 line-clamp-3 leading-relaxed">
                            {{ $reward->description ?? 'Paket sembako berkualitas untuk menunjang kebutuhan pokok keluarga Anda.' }}
                        </p>
                        
                        <!-- Point Price & Affordability Check -->
                        <div class="mb-4">
                            <div class="font-headline-md text-primary font-black text-xl">
                                {{ number_format($reward->point_cost, 0, ',', '.') }} <span class="text-xs font-semibold text-on-surface-variant">Poin</span>
                            </div>
                            
                            @if(auth()->user()->saldo_poin >= $reward->point_cost)
                                <div class="mt-2 inline-flex items-center gap-1.5 text-emerald-700 bg-emerald-50 border border-emerald-100 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide">
                                    <span class="material-symbols-outlined text-[12px] font-bold">check_circle</span>
                                    Bisa Ditukar
                                </div>
                            @else
                                <div class="mt-2 inline-flex items-center gap-1.5 text-error bg-red-50 border border-red-100 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide">
                                    <span class="material-symbols-outlined text-[12px] font-bold">error</span>
                                    Kurang {{ number_format($reward->point_cost - auth()->user()->saldo_poin, 0, ',', '.') }} Pts
                                </div>
                            @endif
                        </div>
                        
                        <!-- Action Stepper & Button -->
                        <div class="flex items-center gap-2 mt-auto pt-4 border-t border-outline-variant/10">
                            <!-- Stepper Input Qty -->
                            <div class="flex items-center border border-outline-variant/50 rounded-xl overflow-hidden bg-white h-10 w-24 shadow-sm">
                                <button type="button" 
                                    onclick="decrementQty({{ $reward->id }})" 
                                    class="w-8 h-full bg-surface-container-low hover:bg-surface-container text-on-surface-variant flex items-center justify-center font-bold transition-colors select-none"
                                    {{ $reward->stock == 0 || auth()->user()->saldo_poin < $reward->point_cost ? 'disabled' : '' }}>
                                    -
                                </button>
                                <input type="number" 
                                    id="qty-{{ $reward->id }}" 
                                    value="{{ $reward->stock == 0 || auth()->user()->saldo_poin < $reward->point_cost ? 0 : 1 }}" 
                                    min="1" 
                                    max="{{ $reward->stock }}" 
                                    readonly
                                    class="w-8 text-center border-none p-0 text-sm font-semibold select-none pointer-events-none focus:ring-0 focus:outline-none">
                                <button type="button" 
                                    onclick="incrementQty({{ $reward->id }}, {{ $reward->stock }})" 
                                    class="w-8 h-full bg-surface-container-low hover:bg-surface-container text-on-surface-variant flex items-center justify-center font-bold transition-colors select-none"
                                    {{ $reward->stock == 0 || auth()->user()->saldo_poin < $reward->point_cost ? 'disabled' : '' }}>
                                    +
                                </button>
                            </div>

                            <!-- Button Tukar -->
                            @if($reward->stock == 0)
                                <button type="button" disabled class="flex-1 h-10 bg-surface-variant text-on-surface-variant/40 rounded-xl text-xs font-bold cursor-not-allowed flex items-center justify-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">block</span>
                                    Stok Habis
                                </button>
                            @elseif(auth()->user()->saldo_poin < $reward->point_cost)
                                <button type="button" disabled class="flex-1 h-10 bg-surface-variant text-on-surface-variant/40 rounded-xl text-xs font-bold cursor-not-allowed flex items-center justify-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">lock</span>
                                    Poin Kurang
                                </button>
                            @else
                                <button type="button" 
                                    onclick="openConfirmModal({{ $reward->id }}, '{{ addslashes($reward->name) }}', {{ $reward->point_cost }}, {{ $reward->stock }})" 
                                    class="flex-1 h-10 bg-primary hover:bg-primary-container text-on-primary rounded-xl text-xs font-bold transition-all shadow-sm flex items-center justify-center gap-1 hover:-translate-y-0.5 active:scale-95">
                                    <span class="material-symbols-outlined text-[16px] font-bold">shopping_cart_checkout</span>
                                    Tukar Sekarang
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Pagination links -->
    @if($rewards->hasPages())
        <div class="mt-8">
            {{ $rewards->links() }}
        </div>
    @endif
</section>

<!-- Single Hidden Submission Form -->
<form id="redemption-submit-form" method="POST" action="{{ route('warga.redemption.store') }}" class="hidden">
    @csrf
    <input type="hidden" name="reward_id" id="form-reward-id">
    <input type="hidden" name="qty" id="form-qty">
    <input type="hidden" name="idempotency_key" id="form-idempotency-key">
</form>

<!-- Modal Konfirmasi Tukar Poin -->
<div id="confirm-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-300">
    <!-- Backdrop Blur overlay -->
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="closeConfirmModal()"></div>
    
    <!-- Modal Container -->
    <div class="relative bg-white rounded-2xl border border-outline-variant/30 shadow-2xl max-w-md w-full overflow-hidden transition-all transform translate-y-4 duration-300" id="confirm-modal-container">
        <div class="p-6">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary font-bold">shopping_basket</span>
                    Konfirmasi Penukaran
                </h3>
                <button type="button" class="p-1.5 hover:bg-surface-container rounded-full text-on-surface-variant transition-colors" onclick="closeConfirmModal()">
                    <span class="material-symbols-outlined text-sm">close</span>
                </button>
            </div>
            
            <!-- Items Details Card -->
            <div class="bg-surface-container-low rounded-xl p-4 border border-outline-variant/20 text-sm space-y-3 mb-5">
                <div class="flex justify-between items-start">
                    <span class="text-on-surface-variant font-semibold">Nama Barang:</span>
                    <span class="font-bold text-on-surface text-right" id="modal-item-name">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-on-surface-variant font-semibold">Jumlah (Qty):</span>
                    <span class="font-bold text-on-surface" id="modal-item-qty">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-on-surface-variant font-semibold">Harga Poin:</span>
                    <span class="font-bold text-on-surface" id="modal-item-cost">- Poin</span>
                </div>
                <div class="border-t border-outline-variant/30 pt-2.5 flex justify-between">
                    <span class="text-on-surface-variant font-semibold">Total Potongan:</span>
                    <span class="font-bold text-error" id="modal-item-total">- Poin</span>
                </div>
            </div>

            <!-- Balances Detail Card -->
            <div class="bg-primary/5 p-4 rounded-xl border border-primary/10 text-xs space-y-2 mb-5">
                <div class="flex justify-between">
                    <span class="text-on-surface-variant font-semibold">Saldo Poin Saat Ini:</span>
                    <span class="font-bold text-on-surface" id="modal-current-balance">- Poin</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-on-surface-variant font-semibold">Sisa Saldo Poin:</span>
                    <span class="font-bold text-primary text-sm" id="modal-remaining-balance">- Poin</span>
                </div>
            </div>
            
            <!-- Warning/Note Box -->
            <div class="flex gap-2.5 items-start bg-yellow-50 border border-yellow-200/50 rounded-xl p-3 text-xs text-yellow-800 mb-6">
                <span class="material-symbols-outlined text-[16px] font-bold mt-0.5">info</span>
                <p class="leading-relaxed">Permintaan penukaran ini akan dikirimkan ke admin dan perlu disetujui sebelum paket dapat diambil.</p>
            </div>

            <!-- Actions buttons -->
            <div class="flex justify-end gap-3">
                <button type="button" 
                    class="px-4 py-2.5 bg-surface-container-low hover:bg-surface-container text-on-surface-variant rounded-xl text-xs font-bold transition-all" 
                    onclick="closeConfirmModal()">
                    Batal
                </button>
                
                <button type="button" 
                    id="submit-redemption-btn" 
                    onclick="submitRedemption()" 
                    class="px-5 py-2.5 bg-primary hover:bg-primary-container text-on-primary rounded-xl text-xs font-bold transition-all shadow-sm flex items-center justify-center gap-1.5 min-w-[120px]">
                    <span id="btn-text">Ya, Tukar</span>
                    <span id="btn-spinner" class="hidden animate-spin h-4 w-4 border-2 border-on-primary border-t-transparent rounded-full"></span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const userSaldo = {{ auth()->user()->saldo_poin }};
    let activeRewardId = null;
    let activePointCost = null;
    
    // Active idempotency storage is handled via sessionStorage

    // Custom Quantity Stepper Control
    function decrementQty(id) {
        const input = document.getElementById('qty-' + id);
        if (!input) return;
        let val = parseInt(input.value) || 1;
        if (val > 1) {
            input.value = val - 1;
        }
    }

    function incrementQty(id, max) {
        const input = document.getElementById('qty-' + id);
        if (!input) return;
        let val = parseInt(input.value) || 1;
        if (val < max) {
            input.value = val + 1;
        }
    }

    // Helper UUID Generator for Idempotency
    function generateUUID() {
        if (window.crypto && window.crypto.randomUUID) {
            return window.crypto.randomUUID();
        }
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    // Confirmation Modal controls
    function openConfirmModal(id, name, cost, stock) {
        const qtyInput = document.getElementById('qty-' + id);
        if (!qtyInput) return;
        const qty = parseInt(qtyInput.value) || 1;
        
        if (qty < 1 || qty > stock) {
            alert("Jumlah penukaran tidak valid.");
            return;
        }
        
        const totalCost = cost * qty;
        if (userSaldo < totalCost) {
            alert("Saldo poin Anda tidak mencukupi untuk melakukan penukaran ini.");
            return;
        }
        
        activeRewardId = id;
        activePointCost = cost;
        
        const formatter = new Intl.NumberFormat('id-ID');
        
        // Populate modal text
        document.getElementById('modal-item-name').textContent = name;
        document.getElementById('modal-item-qty').textContent = qty + " barang";
        document.getElementById('modal-item-cost').textContent = formatter.format(cost) + " Pts";
        document.getElementById('modal-item-total').textContent = formatter.format(totalCost) + " Pts";
        document.getElementById('modal-current-balance').textContent = formatter.format(userSaldo) + " Pts";
        document.getElementById('modal-remaining-balance').textContent = formatter.format(userSaldo - totalCost) + " Pts";
        
        // 1 Reward x 1 Sesi Modal = 1 Idempotency Key
        let storedRewardId = sessionStorage.getItem('redemption_active_reward_id');
        let storedKey = sessionStorage.getItem('redemption_active_idempotency_key');
        
        // Regenerate if first open (no stored key), or if opening a different reward
        if (!storedKey || storedRewardId !== String(id)) {
            storedKey = generateUUID();
            sessionStorage.setItem('redemption_active_reward_id', id);
            sessionStorage.setItem('redemption_active_idempotency_key', storedKey);
        }
        
        // Set hidden form values
        document.getElementById('form-reward-id').value = id;
        document.getElementById('form-qty').value = qty;
        document.getElementById('form-idempotency-key').value = storedKey;
        
        // Open modal animations
        const modal = document.getElementById('confirm-modal');
        const container = document.getElementById('confirm-modal-container');
        
        modal.classList.remove('opacity-0', 'pointer-events-none');
        setTimeout(() => {
            container.classList.remove('translate-y-4');
        }, 10);
    }

    function closeConfirmModal() {
        const modal = document.getElementById('confirm-modal');
        const container = document.getElementById('confirm-modal-container');
        
        container.classList.add('translate-y-4');
        setTimeout(() => {
            modal.classList.add('opacity-0', 'pointer-events-none');
        }, 150);
    }

    // Submit Action
    function submitRedemption() {
        const btn = document.getElementById('submit-redemption-btn');
        const text = document.getElementById('btn-text');
        const spinner = document.getElementById('btn-spinner');
        
        if (btn.disabled) return;
        
        // Block interaction to prevent double submits
        btn.disabled = true;
        btn.classList.add('opacity-70', 'cursor-not-allowed');
        text.textContent = "Memproses...";
        spinner.classList.remove('hidden');
        
        // Submit hidden form
        document.getElementById('redemption-submit-form').submit();
    }
</script>
@endsection
