@extends('layouts.admin')

@section('title', 'Kategori Sampah')

@section('header', 'Kategori Sampah')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-200 flex justify-between items-center bg-slate-50">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Daftar Kategori Sampah</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola jenis sampah dan harga tukar poinnya.</p>
        </div>
        <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors flex items-center gap-2 shadow-sm shadow-emerald-200">
            <span class="material-symbols-rounded text-[18px]">add</span>
            Tambah Kategori
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-200">
                    <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Kategori</th>
                    <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Deskripsi</th>
                    <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Harga/Kg (Rp)</th>
                    <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Poin/Kg</th>
                    <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($categories as $category)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="p-4 font-medium text-slate-800">
                        {{ $category->name }}
                    </td>
                    <td class="p-4 text-sm text-slate-500">
                        {{ Str::limit($category->description, 50) }}
                    </td>
                    <td class="p-4 text-sm font-medium text-slate-700">
                        Rp {{ number_format($category->price_per_kg, 0, ',', '.') }}
                    </td>
                    <td class="p-4 text-sm font-medium text-emerald-600">
                        {{ number_format($category->price_per_kg, 0, ',', '.') }} pts
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                <span class="material-symbols-rounded text-[18px]">edit</span>
                            </button>
                            <button class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                <span class="material-symbols-rounded text-[18px]">delete</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-slate-500">
                        <div class="flex flex-col items-center justify-center">
                            <span class="material-symbols-rounded text-4xl text-slate-300 mb-2">category</span>
                            <p>Belum ada data kategori sampah.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($categories->hasPages())
    <div class="p-4 border-t border-slate-200">
        {{ $categories->links() }}
    </div>
    @endif
</div>
@endsection
