@extends('layouts.admin')

@section('title', isset($kategori) ? 'Edit Kategori' : 'Tambah Kategori')

@section('header', isset($kategori) ? 'Edit Kategori' : 'Tambah Kategori')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden max-w-2xl">
    <div class="p-6 border-b border-slate-200 bg-slate-50">
        <h2 class="text-lg font-bold text-slate-800">{{ isset($kategori) ? 'Edit Kategori Sampah' : 'Tambah Kategori Baru' }}</h2>
        <p class="text-sm text-slate-500 mt-1">Lengkapi form berikut untuk {{ isset($kategori) ? 'mengubah' : 'menambahkan' }} data kategori sampah.</p>
    </div>

    <form action="{{ isset($kategori) ? route('admin.kategori.update', $kategori->id) : route('admin.kategori.store') }}" method="POST" class="p-6 space-y-6">
        @csrf
        @if(isset($kategori))
            @method('PUT')
        @endif

        <!-- Nama Kategori -->
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $kategori->name ?? '') }}" 
                   class="w-full px-4 py-2 border rounded-xl focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-colors {{ $errors->has('name') ? 'border-red-500' : 'border-slate-300' }}"
                   placeholder="Contoh: Plastik Botol" required>
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Deskripsi -->
        <div>
            <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Deskripsi (Opsional)</label>
            <textarea id="description" name="description" rows="3"
                      class="w-full px-4 py-2 border rounded-xl focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-colors {{ $errors->has('description') ? 'border-red-500' : 'border-slate-300' }}"
                      placeholder="Contoh: Botol air mineral bening">{{ old('description', $kategori->description ?? '') }}</textarea>
            @error('description')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Harga per Kg -->
        <div>
            <label for="price_per_kg" class="block text-sm font-medium text-slate-700 mb-1">Harga / Kg (Setara Poin) <span class="text-red-500">*</span></label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-500">
                    Rp
                </div>
                <input type="number" id="price_per_kg" name="price_per_kg" value="{{ old('price_per_kg', $kategori->price_per_kg ?? '') }}" 
                       class="w-full pl-12 pr-4 py-2 border rounded-xl focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-colors {{ $errors->has('price_per_kg') ? 'border-red-500' : 'border-slate-300' }}"
                       placeholder="0" min="0" step="0.01" required>
            </div>
            <p class="text-xs text-slate-500 mt-1">Harga dalam Rupiah yang akan otomatis dikonversi menjadi Poin dengan rasio 1:1.</p>
            @error('price_per_kg')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.kategori') }}" class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm shadow-emerald-200 rounded-xl transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">save</span>
                Simpan Data
            </button>
        </div>
    </form>
</div>
@endsection
