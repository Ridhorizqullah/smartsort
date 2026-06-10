@extends('layouts.admin')

@section('title', 'Data Users')

@section('header', 'Data Users')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-200 flex justify-between items-center bg-slate-50">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Daftar Pengguna</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola data warga dan admin sistem.</p>
        </div>
        <!-- Tambah tombol tambah (Placeholder) -->
        <a href="{{ route('admin.users.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors flex items-center gap-2 shadow-sm shadow-emerald-200">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Tambah Pengguna
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-200">
                    <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama</th>
                    <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                    <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Role</th>
                    <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Saldo Poin</th>
                    <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Bergabung</th>
                    <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-slate-800">{{ $user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $user->nik ?? 'Tidak ada NIK' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-4 text-sm text-slate-600">{{ $user->email }}</td>
                    <td class="p-4 text-sm">
                        @if($user->role === 'admin')
                            <span class="px-2.5 py-1 bg-purple-100 text-purple-700 rounded-lg text-xs font-medium">Admin</span>
                        @else
                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-medium">Warga</span>
                        @endif
                    </td>
                    <td class="p-4 text-sm font-medium text-slate-700">
                        {{ number_format($user->saldo_poin, 0, ',', '.') }} pts
                    </td>
                    <td class="p-4 text-sm text-slate-500">
                        {{ $user->created_at->format('d M Y') }}
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                            </a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-slate-500">
                        <div class="flex flex-col items-center justify-center">
                            <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">person_off</span>
                            <p>Belum ada data user.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($users->hasPages())
    <div class="p-4 border-t border-slate-200">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
