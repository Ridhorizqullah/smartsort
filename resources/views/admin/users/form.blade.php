@extends('layouts.admin')

@section('title', isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna')

@section('header', isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden max-w-2xl">
    <div class="p-6 border-b border-slate-200 bg-slate-50">
        <h2 class="text-lg font-bold text-slate-800">{{ isset($user) ? 'Edit Data Pengguna' : 'Tambah Pengguna Baru' }}</h2>
        <p class="text-sm text-slate-500 mt-1">Lengkapi form berikut untuk {{ isset($user) ? 'mengubah' : 'menambahkan' }} data pengguna sistem.</p>
    </div>

    <form action="{{ isset($user) ? route('admin.users.update', $user->id) : route('admin.users.store') }}" method="POST" class="p-6 space-y-6">
        @csrf
        @if(isset($user))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- NIK -->
            <div>
                <label for="nik" class="block text-sm font-medium text-slate-700 mb-1">NIK (16 Digit) <span class="text-red-500">*</span></label>
                <input type="text" id="nik" name="nik" value="{{ old('nik', $user->nik ?? '') }}" 
                       class="w-full px-4 py-2 border rounded-xl focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-colors {{ $errors->has('nik') ? 'border-red-500' : 'border-slate-300' }}"
                       placeholder="Masukkan 16 digit NIK" required maxlength="16" pattern="\d{16}">
                @error('nik')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama -->
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name ?? '') }}" 
                       class="w-full px-4 py-2 border rounded-xl focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-colors {{ $errors->has('name') ? 'border-red-500' : 'border-slate-300' }}"
                       placeholder="Nama lengkap sesuai KTP" required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" 
                       class="w-full px-4 py-2 border rounded-xl focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-colors {{ $errors->has('email') ? 'border-red-500' : 'border-slate-300' }}"
                       placeholder="Email aktif" required>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Telepon -->
            <div>
                <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">No. HP / WhatsApp <span class="text-red-500">*</span></label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}" 
                       class="w-full px-4 py-2 border rounded-xl focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-colors {{ $errors->has('phone') ? 'border-red-500' : 'border-slate-300' }}"
                       placeholder="Contoh: 0812..." required>
                @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div>
                <label for="role" class="block text-sm font-medium text-slate-700 mb-1">Role Akun <span class="text-red-500">*</span></label>
                <select id="role" name="role" 
                        class="w-full px-4 py-2 border rounded-xl focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-colors bg-white {{ $errors->has('role') ? 'border-red-500' : 'border-slate-300' }}" required>
                    <option value="warga" {{ old('role', $user->role ?? '') === 'warga' ? 'selected' : '' }}>Warga (Nasabah)</option>
                    <option value="petugas" {{ old('role', $user->role ?? '') === 'petugas' ? 'selected' : '' }}>Petugas</option>
                    <option value="admin" {{ old('role', $user->role ?? '') === 'admin' ? 'selected' : '' }}>Administrator</option>
                </select>
                @error('role')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- RT/RW -->
            <div>
                <label for="rt_rw" class="block text-sm font-medium text-slate-700 mb-1">RT / RW <span class="text-red-500">*</span></label>
                <input type="text" id="rt_rw" name="rt_rw" value="{{ old('rt_rw', $user->rt_rw ?? '') }}" 
                       class="w-full px-4 py-2 border rounded-xl focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-colors {{ $errors->has('rt_rw') ? 'border-red-500' : 'border-slate-300' }}"
                       placeholder="Contoh: 01/05" required>
                @error('rt_rw')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Alamat -->
        <div>
            <label for="address" class="block text-sm font-medium text-slate-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
            <textarea id="address" name="address" rows="3"
                      class="w-full px-4 py-2 border rounded-xl focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-colors {{ $errors->has('address') ? 'border-red-500' : 'border-slate-300' }}"
                      placeholder="Alamat tempat tinggal lengkap" required>{{ old('address', $user->address ?? '') }}</textarea>
            @error('address')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password (Optional on Edit) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-slate-50 border border-slate-200 rounded-xl">
            <div class="md:col-span-2">
                <p class="text-sm font-medium text-slate-800">Pengaturan Password</p>
                @if(isset($user))
                <p class="text-xs text-slate-500 mt-1">Kosongkan jika tidak ingin mengubah password.</p>
                @else
                <p class="text-xs text-slate-500 mt-1">Password default akun baru.</p>
                @endif
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password {{ !isset($user) ? '*' : '' }}</label>
                <input type="password" id="password" name="password" 
                       class="w-full px-4 py-2 border rounded-xl focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-colors {{ $errors->has('password') ? 'border-red-500' : 'border-slate-300' }}"
                       {{ !isset($user) ? 'required' : '' }}>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password {{ !isset($user) ? '*' : '' }}</label>
                <input type="password" id="password_confirmation" name="password_confirmation" 
                       class="w-full px-4 py-2 border rounded-xl focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-colors border-slate-300"
                       {{ !isset($user) ? 'required' : '' }}>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.users') }}" class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
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
