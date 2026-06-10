@extends('layouts.admin')

@section('title', 'Katalog Reward')

@section('header', 'Katalog Reward')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-200 flex justify-between items-center bg-slate-50">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Daftar Reward</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola item reward yang dapat ditukarkan dengan poin.</p>
        </div>
        <a href="{{ route('admin.reward.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors flex items-center gap-2 shadow-sm shadow-emerald-200">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Tambah Reward
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-200">
                    <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Reward</th>
                    <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Poin Dibutuhkan</th>
                    <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Stok</th>
                    <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($rewards as $reward)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="p-4 font-medium text-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                                <span class="material-symbols-outlined text-[20px]">card_giftcard</span>
                            </div>
                            <span>{{ $reward->name }}</span>
                        </div>
                    </td>
                    <td class="p-4 text-sm font-medium text-emerald-600">
                        {{ number_format($reward->point_cost, 0, ',', '.') }} pts
                    </td>
                    <td class="p-4 text-sm">
                        @if($reward->stock > 0)
                            <span class="px-2.5 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-medium">{{ $reward->stock }} Item</span>
                        @else
                            <span class="px-2.5 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-medium">Habis</span>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.reward.edit', $reward->id) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                            </a>
                            <form action="{{ route('admin.reward.destroy', $reward->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus reward ini?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-slate-500">
                        <div class="flex flex-col items-center justify-center">
                            <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">redeem</span>
                            <p>Belum ada data reward.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($rewards->hasPages())
    <div class="p-4 border-t border-slate-200">
        {{ $rewards->links() }}
    </div>
    @endif
</div>
@endsection
