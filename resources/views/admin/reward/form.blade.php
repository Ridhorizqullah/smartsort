@extends('layouts.admin')

@section('title', isset($reward) ? 'Edit Reward' : 'Tambah Reward')

@section('header', isset($reward) ? 'Edit Reward' : 'Tambah Reward')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden max-w-2xl">
    <div class="p-6 border-b border-slate-200 bg-slate-50">
        <h2 class="text-lg font-bold text-slate-800">{{ isset($reward) ? 'Edit Data Reward' : 'Tambah Reward Baru' }}</h2>
        <p class="text-sm text-slate-500 mt-1">Lengkapi form berikut untuk {{ isset($reward) ? 'mengubah' : 'menambahkan' }} data reward.</p>
    </div>

    <form action="{{ isset($reward) ? route('admin.reward.update', $reward->id) : route('admin.reward.store') }}" method="POST" class="p-6 space-y-6">
        @csrf
        @if(isset($reward))
            @method('PUT')
        @endif

        <!-- Nama Reward -->
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Reward <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $reward->name ?? '') }}" 
                   class="w-full px-4 py-2 border rounded-xl focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-colors {{ $errors->has('name') ? 'border-red-500' : 'border-slate-300' }}"
                   placeholder="Contoh: Beras 5kg" required>
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Poin Dibutuhkan -->
        <div>
            <label for="point_cost" class="block text-sm font-medium text-slate-700 mb-1">Poin Dibutuhkan <span class="text-red-500">*</span></label>
            <div class="relative">
                <input type="number" id="point_cost" name="point_cost" value="{{ old('point_cost', $reward->point_cost ?? '') }}" 
                       class="w-full pl-4 pr-12 py-2 border rounded-xl focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-colors {{ $errors->has('point_cost') ? 'border-red-500' : 'border-slate-300' }}"
                       placeholder="0" min="0" required>
                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-500 text-sm">
                    pts
                </div>
            </div>
            @error('point_cost')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Stok -->
        <div>
            <label for="stock" class="block text-sm font-medium text-slate-700 mb-1">Stok Barang <span class="text-red-500">*</span></label>
            <div class="relative">
                <input type="number" id="stock" name="stock" value="{{ old('stock', $reward->stock ?? '') }}" 
                       class="w-full pl-4 pr-16 py-2 border rounded-xl focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-colors {{ $errors->has('stock') ? 'border-red-500' : 'border-slate-300' }}"
                       placeholder="0" min="0" required>
                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-500 text-sm">
                    Item
                </div>
            </div>
            @error('stock')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.reward') }}" class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
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
