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
            
            <!-- Pilih Warga (Searchable Select Component) -->
            <div class="mb-8 relative" id="wargaDropdownContainer">
                <label class="text-sm font-bold text-on-surface-variant mb-2 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm text-primary">person</span>
                    Pilih Warga (Nasabah)
                </label>
                
                <input type="hidden" name="user_id" id="selected_warga_id" value="{{ old('user_id') }}" required>
                
                <div class="relative">
                    <!-- Trigger Button / Display -->
                    <button type="button" id="wargaDropdownTrigger" class="w-full flex items-center justify-between border border-outline-variant/50 rounded-xl px-4 py-3 bg-white text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all cursor-pointer text-left">
                        <span id="selected_warga_label" class="text-on-surface-variant">-- Cari atau Pilih Warga --</span>
                        <span id="wargaDropdownArrow" class="material-symbols-outlined text-on-surface-variant transition-transform duration-200">expand_more</span>
                    </button>
                    
                    <!-- Dropdown Menu Box -->
                    <div id="wargaDropdownMenu" class="absolute left-0 right-0 mt-2 bg-white border border-outline-variant/30 rounded-xl shadow-lg z-50 opacity-0 pointer-events-none transition-all duration-200 transform -translate-y-2">
                        <!-- Search Input -->
                        <div class="p-3 border-b border-outline-variant/20 flex items-center gap-2 bg-slate-50 rounded-t-xl">
                            <span class="material-symbols-outlined text-slate-400 text-[20px]">search</span>
                            <input type="text" id="wargaSearchInput" class="w-full bg-transparent border-none text-sm outline-none placeholder-slate-400" placeholder="Ketik nama, NIK, atau RT/RW warga..." autocomplete="off">
                        </div>
                        
                        <!-- Options List -->
                        <div class="max-h-60 overflow-y-auto py-1" id="wargaOptionsList">
                            <div class="px-4 py-3 text-sm text-slate-500 hover:bg-slate-50 cursor-pointer select-none border-b border-slate-100/50 warga-option-clear" data-value="" data-label="-- Cari atau Pilih Warga --">
                                -- Batalkan Pilihan --
                            </div>
                            @foreach($wargaList as $warga)
                                <div class="px-4 py-3 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 cursor-pointer transition-colors border-b border-slate-100/50 last:border-none warga-option" 
                                     data-value="{{ $warga->id }}" 
                                     data-label="{{ $warga->name }} (NIK: {{ $warga->nik }} | RT/RW: {{ $warga->rt_rw ?? '-' }})"
                                     data-search="{{ strtolower($warga->name . ' ' . $warga->nik . ' ' . ($warga->rt_rw ?? '')) }}">
                                    <div class="font-semibold">{{ $warga->name }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">NIK: {{ $warga->nik }} • RT/RW: {{ $warga->rt_rw ?? '-' }}</div>
                                </div>
                            @endforeach
                            <!-- No Results State -->
                            <div id="noWargaResults" class="px-4 py-6 text-center text-sm text-slate-500 hidden flex flex-col items-center">
                                <span class="material-symbols-outlined text-slate-300 text-3xl mb-1">person_search</span>
                                Warga tidak ditemukan
                            </div>
                        </div>
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

        // Searchable Warga Dropdown Logic
        const wargaContainer = document.getElementById('wargaDropdownContainer');
        const wargaTrigger = document.getElementById('wargaDropdownTrigger');
        const wargaMenu = document.getElementById('wargaDropdownMenu');
        const wargaSearchInput = document.getElementById('wargaSearchInput');
        const wargaOptionsList = document.getElementById('wargaOptionsList');
        const selectedWargaId = document.getElementById('selected_warga_id');
        const selectedWargaLabel = document.getElementById('selected_warga_label');
        const wargaDropdownArrow = document.getElementById('wargaDropdownArrow');
        const noWargaResults = document.getElementById('noWargaResults');
        const wargaOptions = wargaOptionsList.querySelectorAll('.warga-option');
        const wargaOptionClear = wargaOptionsList.querySelector('.warga-option-clear');

        let activeOptionIndex = -1;

        // Toggle dropdown open/close
        wargaTrigger.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = !wargaMenu.classList.contains('opacity-0');
            if (isOpen) {
                closeWargaDropdown();
            } else {
                openWargaDropdown();
            }
        });

        function openWargaDropdown() {
            wargaMenu.classList.remove('opacity-0', 'pointer-events-none', '-translate-y-2');
            wargaMenu.classList.add('opacity-100', 'translate-y-0');
            if (wargaDropdownArrow) wargaDropdownArrow.classList.add('rotate-180');
            wargaSearchInput.focus();
            activeOptionIndex = -1;
            highlightActiveOption();
        }

        function closeWargaDropdown() {
            wargaMenu.classList.remove('opacity-100', 'translate-y-0');
            wargaMenu.classList.add('opacity-0', 'pointer-events-none', '-translate-y-2');
            if (wargaDropdownArrow) wargaDropdownArrow.classList.remove('rotate-180');
            wargaSearchInput.value = '';
            // Reset filter
            wargaOptions.forEach(opt => {
                opt.classList.remove('hidden', 'bg-emerald-50', 'text-emerald-700');
            });
            if (wargaOptionClear) wargaOptionClear.classList.remove('hidden', 'bg-slate-100');
            noWargaResults.classList.add('hidden');
            activeOptionIndex = -1;
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!wargaContainer.contains(e.target)) {
                closeWargaDropdown();
            }
        });

        // Search filter logic
        wargaSearchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            let hasResults = false;

            wargaOptions.forEach(opt => {
                const searchData = opt.getAttribute('data-search');
                if (searchData.includes(query)) {
                    opt.classList.remove('hidden');
                    hasResults = true;
                } else {
                    opt.classList.add('hidden');
                }
            });

            if (wargaOptionClear) {
                if (query === '') {
                    wargaOptionClear.classList.remove('hidden');
                } else {
                    wargaOptionClear.classList.add('hidden');
                }
            }

            if (hasResults || query === '') {
                noWargaResults.classList.add('hidden');
            } else {
                noWargaResults.classList.remove('hidden');
            }

            activeOptionIndex = -1;
            highlightActiveOption();
        });

        // Keyboard navigation within search input
        wargaSearchInput.addEventListener('keydown', function(e) {
            const visibleOptions = Array.from(wargaOptionsList.querySelectorAll('.warga-option, .warga-option-clear'))
                .filter(opt => !opt.classList.contains('hidden'));

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                activeOptionIndex = (activeOptionIndex + 1) % visibleOptions.length;
                highlightActiveOption(visibleOptions);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                activeOptionIndex = (activeOptionIndex - 1 + visibleOptions.length) % visibleOptions.length;
                highlightActiveOption(visibleOptions);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (activeOptionIndex >= 0 && activeOptionIndex < visibleOptions.length) {
                    visibleOptions[activeOptionIndex].click();
                } else if (visibleOptions.length > 0) {
                    visibleOptions[0].click();
                }
            } else if (e.key === 'Escape') {
                closeWargaDropdown();
            }
        });

        function highlightActiveOption(customList) {
            const visibleOptions = customList || Array.from(wargaOptionsList.querySelectorAll('.warga-option, .warga-option-clear'))
                .filter(opt => !opt.classList.contains('hidden'));

            visibleOptions.forEach((opt, idx) => {
                if (idx === activeOptionIndex) {
                    if (opt.classList.contains('warga-option-clear')) {
                        opt.classList.add('bg-slate-100');
                    } else {
                        opt.classList.add('bg-emerald-50', 'text-emerald-700');
                    }
                    opt.scrollIntoView({ block: 'nearest' });
                } else {
                    opt.classList.remove('bg-slate-100', 'bg-emerald-50', 'text-emerald-700');
                }
            });
        }

        // Select option logic
        wargaOptionsList.addEventListener('click', function(e) {
            const target = e.target.closest('[data-value]');
            if (!target) return;

            const val = target.getAttribute('data-value');
            const label = target.getAttribute('data-label');

            selectedWargaId.value = val;
            selectedWargaLabel.textContent = label;
            
            if (val === '') {
                selectedWargaLabel.classList.add('text-on-surface-variant');
                selectedWargaLabel.classList.remove('text-on-surface', 'font-bold');
            } else {
                selectedWargaLabel.classList.remove('text-on-surface-variant');
                selectedWargaLabel.classList.add('text-on-surface', 'font-bold');
            }

            closeWargaDropdown();
        });

        // Set initial selected value if old() exists
        const initialVal = selectedWargaId.value;
        if (initialVal) {
            const matchedOpt = Array.from(wargaOptions).find(opt => opt.getAttribute('data-value') == initialVal);
            if (matchedOpt) {
                selectedWargaLabel.textContent = matchedOpt.getAttribute('data-label');
                selectedWargaLabel.classList.remove('text-on-surface-variant');
                selectedWargaLabel.classList.add('text-on-surface', 'font-bold');
            }
        }
    });
</script>
@endpush
