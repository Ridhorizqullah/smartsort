<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\WasteCategory;
use App\Models\Transaction;
use App\Models\Redemption;
use App\Models\Reward;
use App\Services\TransactionService;
use App\Services\RedemptionService;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function __construct()
    {
        // Sinkron dengan route group: admin DAN petugas bisa akses panel ini
        $this->middleware(['auth', 'role:admin,petugas']);
    }

    public function dashboard()
    {
        // Gabungkan 2 query warga menjadi 1 selectRaw untuk efisiensi
        $wargaStats = User::role('warga')
            ->selectRaw('COUNT(*) as total, COALESCE(SUM(saldo_poin), 0) as total_poin')
            ->first();

        $totalWarga       = $wargaStats->total ?? 0;
        $totalPoinBeredar = $wargaStats->total_poin ?? 0;
        $totalTransaksi   = Transaction::count();
        $pendingPenukaran = Redemption::where('status', 'pending')->count();
        
        $recentTransactions = Transaction::with('user:id,name,nik')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalWarga', 'totalTransaksi', 'totalPoinBeredar', 'pendingPenukaran', 'recentTransactions'
        ));
    }

    public function transaksiForm()
    {
        // Hanya ambil kolom yang ditampilkan di dropdown (hindari SELECT * + data sensitif)
        $wargaList = User::role('warga')
            ->select('id', 'name', 'nik', 'rt_rw')
            ->orderBy('name')
            ->get();

        $categories = WasteCategory::select('id', 'name', 'price_per_kg')->get();
        
        return view('admin.transaksi.form', compact('wargaList', 'categories'));
    }

    public function transaksiStore(Request $request, TransactionService $transactionService)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.waste_category_id' => 'required|integer|exists:waste_categories,id',
            'items.*.weight' => 'required|numeric|min:0.1|max:9999',
            'idempotency_key' => ['required', 'string', 'uuid'],
        ]);

        if (empty($request->items)) {
            return back()->with('error', 'Item tidak boleh kosong');
        }

        try {
            $transactionService->createTransaction(
                Auth::id(),
                $request->user_id,
                $request->items,
                $request->idempotency_key
            );

            return back()->with('success', 'Transaksi berhasil disimpan!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function redemptionList()
    {
        $redemptions = Redemption::with(['user', 'details.reward'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.penukaran.index', compact('redemptions'));
    }

    public function approveRedemption(Request $request, $id, RedemptionService $redemptionService)
    {
        if (!$id) abort(404);

        $request->validate([
            'tanggal_ambil' => 'required|date|after_or_equal:today',
        ]);

        try {
            $redemptionService->approveRedemption($id, Auth::id(), $request->tanggal_ambil);
            return back()->with('success', 'Penukaran berhasil disetujui!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function rejectRedemption(Request $request, $id, RedemptionService $redemptionService)
    {
        if (!$id) abort(404);

        $request->validate([
            'catatan' => 'required|string|max:255',
        ]);

        try {
            $redemptionService->rejectRedemption($id, Auth::id(), $request->catatan);
            return back()->with('success', 'Penukaran berhasil ditolak.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function users()
    {
        // Filter hanya warga, ambil kolom yang relevan saja (bukan SELECT *)
        $users = User::role('warga')
            ->select('id', 'name', 'nik', 'email', 'rt_rw', 'saldo_poin', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function kategori()
    {
        $categories = WasteCategory::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.kategori.index', compact('categories'));
    }

    public function reward()
    {
        $rewards = Reward::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.reward.index', compact('rewards'));
    }
}
