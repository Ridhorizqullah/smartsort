<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Halaman dashboard utama warga.
     * Menampilkan: saldo poin, total credit, total debit.
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Hitung saldo kredit & debit dari ledger secara langsung (efisien, 2 query)
        $totalCredit = $user->pointLedgers()->where('type', 'credit')->sum('amount');
        $totalDebit  = $user->pointLedgers()->where('type', 'debit')->sum('amount');
        $saldo       = (int) ($totalCredit - $totalDebit);

        // Ambil 5 transaksi terbaru untuk preview di dashboard
        $recentTransactions = $user->transactions()
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard', compact('user', 'saldo', 'totalCredit', 'totalDebit', 'recentTransactions'));
    }

    /**
     * Halaman riwayat setor sampah.
     */
    public function transactions()
    {
        $user = Auth::user();

        $transactions = $user->transactions()
            ->with('details.wasteCategory') // eager load untuk menghindari N+1
            ->latest()
            ->paginate(10);

        return view('user.transactions', compact('user', 'transactions'));
    }

    /**
     * Halaman riwayat penukaran sembako.
     */
    public function redemptions()
    {
        $user = Auth::user();

        $redemptions = $user->redemptions()
            ->with('details.reward') // eager load untuk menghindari N+1
            ->latest()
            ->paginate(10);

        return view('user.redemptions', compact('user', 'redemptions'));
    }

    /**
     * Halaman katalog reward (sembako) yang tersedia.
     */
    public function rewards()
    {
        $user = Auth::user();

        $rewards = Reward::orderBy('point_cost')->get();

        $saldo = $user->saldo_poin;

        return view('user.rewards', compact('user', 'rewards', 'saldo'));
    }
}
