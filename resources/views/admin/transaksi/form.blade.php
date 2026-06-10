@extends('layouts.admin')

@section('title', 'POS Timbangan - SmartSort')
@section('header', 'POS Transaksi Timbangan')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
    
    <!-- Bagian Form POS -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-outline-variant/30 p-8 shadow-sm">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-xl font-bold text-on-surface">Input Setoran Sampah</h2>
                <p class="text-xs text-on-surface-variant mt-1">Gunakan modul ini untuk menimbang dan mencatat transaksi masuk warga.</p>
            </div>
            <div class="bg-primary/10 text-primary p-3 rounded-2xl flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px]">scale</span>
            </div>
        </div>
        
        <form action="{{ route('admin.transaksi.store') }}" method="POST" id="posForm">
            @csrf
            <input type="hidden" name="idempotency_key" value="{{ Str::uuid() }}">
            
            <!-- Pilih Warga -->
            <div class="mb-8">
                <label class="text-sm font-bold text-on-surface-variant mb-2 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm text-primary">person</span>
                    Pilih Warga (Nasabah)
                </label>
                <div class="relative">
                    <select name="user_id" id="user_select" required class="w-full border border-outline-variant/50 rounded-xl px-4 py-3 bg-white text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all appearance-none cursor-pointer">
                        <option value="">-- Cari atau Pilih Warga --</option>
                        @foreach($wargaList as $warga)
                            <option value="{{ $warga->id }}" {{ old('user_id') == $warga->id ? 'selected' : '' }}>
                                {{ $warga->name }} (NIK: {{ $warga->nik }} | RT/RW: {{ $warga->rt_rw ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-on-surface-variant">
                        <span class="material-symbols-outlined">expand_more</span>
                    </div>
                </div>
                @error('user_id') <p class="text-error text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="border-t border-outline-variant/30 my-8"></div>

            <!-- Header Repeater -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-on-surface uppercase tracking-wider flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm text-primary">shopping_bag</span>
                    Rincian Timbangan
                </h3>
            </div>

            <!-- Dynamic Items Container -->
            <div id="itemsContainer" class="space-y-4">
                <!-- Template Item Row -->
                <div class="flex items-start gap-4 p-4 rounded-xl border border-outline-variant/30 bg-surface-container-lowest/50 item-row relative">
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-on-surface-variant mb-1.5">Kategori Sampah</label>
                        <div class="relative">
                            <select name="items[0][waste_category_id]" required class="w-full border border-outline-variant/50 rounded-xl px-3 py-2.5 bg-white text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all appearance-none category-select cursor-pointer">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" data-price="{{ $cat->price_per_kg }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-on-surface-variant">
                                <span class="material-symbols-outlined text-sm">expand_more</span>
                            </div>
                        </div>
                        <div class="text-[11px] text-on-surface-variant/80 mt-1 font-semibold price-indicator hidden">Harga: Rp <span class="price-val">0</span> / kg</div>
                    </div>
                    <div class="w-1/3">
                        <label class="block text-xs font-bold text-on-surface-variant mb-1.5">Berat (Kg)</label>
                        <input type="number" step="0.1" min="0.1" name="items[0][weight]" required class="w-full border border-outline-variant/50 rounded-xl px-3 py-2.5 bg-white text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all weight-input" placeholder="0.0">
                    </div>
                    <div class="pt-6">
                        <button type="button" class="w-10 h-10 flex items-center justify-center bg-error/10 text-error rounded-xl hover:bg-error hover:text-white transition-all remove-btn" disabled>
                            <span class="material-symbols-outlined text-lg">delete</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tombol Tambah Item -->
            <button type="button" id="addItemBtn" class="mt-4 text-xs font-bold text-primary flex items-center gap-1.5 hover:text-primary-container transition-colors py-2 px-3 bg-primary/5 rounded-lg border border-primary/10">
                <span class="material-symbols-outlined text-sm">add_circle</span>
                Tambah Jenis Sampah
            </button>

            <!-- Tombol Submit -->
            <div class="mt-8 flex justify-end pt-4 border-t border-outline-variant/30">
                <button type="submit" class="bg-primary text-on-primary hover:bg-primary-container px-8 py-3.5 rounded-xl font-bold transition-all shadow-sm hover:shadow-md active:scale-95 flex items-center gap-2 text-sm">
                    <span class="material-symbols-outlined text-lg">save</span>
                    Simpan Transaksi Timbangan
                </button>
            </div>
        </form>
    </div>

    <!-- Panel Informasi & Estimasi -->
    <div class="flex flex-col gap-6">
        
        <!-- Live Estimasi Poin -->
        <div class="bg-white rounded-2xl border border-outline-variant/30 p-6 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-bl-full flex items-center justify-center pointer-events-none">
                <span class="material-symbols-outlined text-primary/20 text-3xl">calculate</span>
            </div>
            <h3 class="font-bold text-on-surface mb-1 flex items-center gap-2">
                Estimasi Poin
            </h3>
            <p class="text-xs text-on-surface-variant mb-4">Estimasi total poin yang akan didapatkan warga.</p>
            
            <div class="border-t border-outline-variant/30 my-4"></div>
            
            <div class="text-center py-4">
                <div class="text-[40px] font-extrabold text-primary tracking-tight leading-none" id="totalEstimate">0</div>
                <div class="text-xs text-on-surface-variant mt-2 font-semibold">Total Poin Masuk</div>
            </div>
            
            <div class="border-t border-outline-variant/30 my-4"></div>
            
            <div class="space-y-2 max-h-40 overflow-y-auto text-xs" id="itemsSummary">
                <p class="text-on-surface-variant text-center py-2">Belum ada item timbangan yang valid.</p>
            </div>
        </div>

        <!-- Panduan Kasir -->
        <div class="bg-white rounded-2xl border border-outline-variant/30 p-6 shadow-sm">
            <h3 class="font-bold text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-xl">help</span>
                Panduan Operasional
            </h3>
            <ul class="space-y-3.5 text-xs text-on-surface-variant">
                <li class="flex items-start gap-2.5">
                    <span class="material-symbols-outlined text-primary text-sm mt-0.5">check_circle</span>
                    <span>Pastikan jarum timbangan berada di posisi <strong>Nol (0)</strong> sebelum menaruh wadah sampah.</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="material-symbols-outlined text-primary text-sm mt-0.5">check_circle</span>
                    <span>Tanyakan nama atau <strong>NIK</strong> warga terlebih dahulu untuk menghindari salah rekap akun.</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="material-symbols-outlined text-primary text-sm mt-0.5">check_circle</span>
                    <span>Wajib memilah sampah sesuai kategorinya sebelum ditimbang demi akurasi harga/poin.</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let itemCount = 1;
        const container = document.getElementById('itemsContainer');
        const addBtn = document.getElementById('addItemBtn');
        const totalEstimateDiv = document.getElementById('totalEstimate');
        const itemsSummaryDiv = document.getElementById('itemsSummary');

        // Pilihan Kategori JSON untuk JS
        const categories = @json($categories);

        // Update live kalkulasi estimasi poin
        function calculateLiveTotal() {
            let total = 0;
            let summaryHTML = '';
            let validRows = 0;
            
            const rows = container.querySelectorAll('.item-row');
            rows.forEach((row) => {
                const catSelect = row.querySelector('.category-select');
                const weightInput = row.querySelector('.weight-input');
                const priceIndicator = row.querySelector('.price-indicator');
                const priceValSpan = row.querySelector('.price-val');
                
                const catId = catSelect.value;
                const weight = parseFloat(weightInput.value);
                
                if (catId) {
                    const category = categories.find(c => c.id == catId);
                    if (category) {
                        // Tampilkan harga per kg
                        priceIndicator.classList.remove('hidden');
                        priceValSpan.textContent = new Intl.NumberFormat('id-ID').format(category.price_per_kg);
                        
                        if (weight && weight > 0) {
                            const subtotal = weight * category.price_per_kg;
                            total += subtotal;
                            validRows++;
                            
                            summaryHTML += `
                                <div class="flex items-center justify-between py-1 border-b border-outline-variant/10">
                                    <span class="text-on-surface-variant font-semibold">${category.name} (${weight} kg)</span>
                                    <span class="font-bold text-primary">+${new Intl.NumberFormat('id-ID').format(subtotal)}</span>
                                </div>
                            `;
                        }
                    }
                } else {
                    priceIndicator.classList.add('hidden');
                }
            });
            
            totalEstimateDiv.textContent = new Intl.NumberFormat('id-ID').format(total);
            
            if (validRows > 0) {
                itemsSummaryDiv.innerHTML = summaryHTML;
            } else {
                itemsSummaryDiv.innerHTML = '<p class="text-on-surface-variant text-center py-2">Belum ada item timbangan yang valid.</p>';
            }
        }

        // Attach event listener ke elemen dinamis
        function attachRowEvents(row) {
            const select = row.querySelector('.category-select');
            const input = row.querySelector('.weight-input');
            const removeBtn = row.querySelector('.remove-btn');
            
            select.addEventListener('change', calculateLiveTotal);
            input.addEventListener('input', calculateLiveTotal);
            
            if (removeBtn) {
                removeBtn.addEventListener('click', function() {
                    row.remove();
                    updateRemoveButtonsState();
                    calculateLiveTotal();
                });
            }
        }

        // Aktifkan / Matikan tombol hapus baris pertama jika hanya 1 baris
        function updateRemoveButtonsState() {
            const rows = container.querySelectorAll('.item-row');
            const firstRowRemoveBtn = rows[0].querySelector('.remove-btn');
            if (rows.length === 1) {
                firstRowRemoveBtn.disabled = true;
            } else {
                firstRowRemoveBtn.disabled = false;
            }
        }

        // Event handler tambah baris
        addBtn.addEventListener('click', function() {
            const row = document.createElement('div');
            row.className = 'flex items-start gap-4 p-4 rounded-xl border border-outline-variant/30 bg-surface-container-lowest/50 item-row relative';
            row.innerHTML = `
                <div class="flex-1">
                    <label class="block text-xs font-bold text-on-surface-variant mb-1.5">Kategori Sampah</label>
                    <div class="relative">
                        <select name="items[${itemCount}][waste_category_id]" required class="w-full border border-outline-variant/50 rounded-xl px-3 py-2.5 bg-white text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all appearance-none category-select cursor-pointer">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-on-surface-variant">
                            <span class="material-symbols-outlined text-sm">expand_more</span>
                        </div>
                    </div>
                    <div class="text-[11px] text-on-surface-variant/80 mt-1 font-semibold price-indicator hidden">Harga: Rp <span class="price-val">0</span> / kg</div>
                </div>
                <div class="w-1/3">
                    <label class="block text-xs font-bold text-on-surface-variant mb-1.5">Berat (Kg)</label>
                    <input type="number" step="0.1" min="0.1" name="items[${itemCount}][weight]" required class="w-full border border-outline-variant/50 rounded-xl px-3 py-2.5 bg-white text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all weight-input" placeholder="0.0">
                </div>
                <div class="pt-6">
                    <button type="button" class="w-10 h-10 flex items-center justify-center bg-error/10 text-error rounded-xl hover:bg-error hover:text-white transition-all remove-btn">
                        <span class="material-symbols-outlined text-lg">delete</span>
                    </button>
                </div>
            `;
            container.appendChild(row);
            itemCount++;
            
            attachRowEvents(row);
            updateRemoveButtonsState();
            calculateLiveTotal();
        });

        // Inisialisasi baris awal
        const initialRow = container.querySelector('.item-row');
        attachRowEvents(initialRow);
        updateRemoveButtonsState();
    });
</script>
@endpush
