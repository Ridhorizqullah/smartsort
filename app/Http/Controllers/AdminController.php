<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApproveRedemptionRequest;
use App\Http\Requests\RejectRedemptionRequest;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Redemption;
use App\Models\Reward;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WasteCategory;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\Admin\StoreKategoriRequest;
use App\Http\Requests\Admin\UpdateKategoriRequest;
use App\Http\Requests\Admin\StoreRewardRequest;
use App\Http\Requests\Admin\UpdateRewardRequest;
use App\Services\RedemptionService;
use App\Services\TransactionService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    /**
     * Dashboard Admin: statistik utama & transaksi terbaru.
     */
    public function dashboard()
    {
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

    /**
     * Form POS Timbangan Sampah.
     */
    public function transaksiForm()
    {
        $wargaList = User::role('warga')
            ->select('id', 'name', 'nik', 'rt_rw')
            ->orderBy('name')
            ->get();

        $categories = WasteCategory::select('id', 'name', 'price_per_kg')->get();

        return view('admin.transaksi.form', compact('wargaList', 'categories'));
    }

    /**
     * Simpan transaksi setoran sampah.
     * Validasi ditangani StoreTransactionRequest (FormRequest).
     */
    public function transaksiStore(StoreTransactionRequest $request, TransactionService $transactionService)
    {
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

    /**
     * Daftar penukaran yang masuk (pending, dll).
     */
    public function redemptionList()
    {
        $redemptions = Redemption::with(['user', 'details.reward'])
            ->orderBy('created_at', 'desc')
            ->paginate(config('business.pagination_size', 10));

        return view('admin.penukaran.index', compact('redemptions'));
    }

    /**
     * Approve penukaran warga.
     * Validasi ditangani ApproveRedemptionRequest (FormRequest).
     */
    public function approveRedemption(ApproveRedemptionRequest $request, $id, RedemptionService $redemptionService)
    {
        abort_if(!$id, 404);

        try {
            $redemptionService->approveRedemption($id, Auth::id(), $request->tanggal_ambil);
            return back()->with('success', 'Penukaran berhasil disetujui!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Tolak penukaran warga.
     * Validasi ditangani RejectRedemptionRequest (FormRequest).
     */
    public function rejectRedemption(RejectRedemptionRequest $request, $id, RedemptionService $redemptionService)
    {
        abort_if(!$id, 404);

        try {
            $redemptionService->rejectRedemption($id, Auth::id(), $request->catatan);
            return back()->with('success', 'Penukaran berhasil ditolak.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // ==========================================
    // CRUD PENGGUNA (USERS)
    // ==========================================

    public function users()
    {
        $this->authorizeAdmin();
        
        $query = User::orderBy('created_at', 'desc');

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('rt_rw', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(config('business.pagination_size', 10))
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function userCreate()
    {
        $this->authorizeAdmin();
        return view('admin.users.form');
    }

    public function userStore(StoreUserRequest $request)
    {
        $this->authorizeAdmin();
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        User::create($data);
        return redirect()->route('admin.users')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function userEdit($id)
    {
        $this->authorizeAdmin();
        $user = User::findOrFail($id);
        return view('admin.users.form', compact('user'));
    }

    public function userUpdate(UpdateUserRequest $request, $id)
    {
        $this->authorizeAdmin();
        $user = User::findOrFail($id);
        $data = $request->validated();
        
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        return redirect()->route('admin.users')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function userDestroy($id)
    {
        $this->authorizeAdmin();
        $user = User::findOrFail($id);
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat menghapus akun Anda sendiri.');
        }
        
        // Proteksi integritas data riwayat transaksi
        if ($user->transactions()->exists() || $user->redemptions()->exists()) {
            return back()->with('error', 'Pengguna tidak dapat dihapus karena memiliki riwayat transaksi atau penukaran sampah aktif.');
        }

        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Pengguna berhasil dihapus.');
    }

    // ==========================================
    // CRUD KATEGORI SAMPAH
    // ==========================================

    public function kategori()
    {
        $this->authorizeAdmin();
        $categories = WasteCategory::orderBy('created_at', 'desc')
            ->paginate(config('business.pagination_size', 10));
        return view('admin.kategori.index', compact('categories'));
    }

    public function kategoriCreate()
    {
        $this->authorizeAdmin();
        return view('admin.kategori.form');
    }

    public function kategoriStore(StoreKategoriRequest $request)
    {
        $this->authorizeAdmin();
        WasteCategory::create($request->validated());
        return redirect()->route('admin.kategori')->with('success', 'Kategori sampah berhasil ditambahkan.');
    }

    public function kategoriEdit($id)
    {
        $this->authorizeAdmin();
        $kategori = WasteCategory::findOrFail($id);
        return view('admin.kategori.form', compact('kategori'));
    }

    public function kategoriUpdate(UpdateKategoriRequest $request, $id)
    {
        $this->authorizeAdmin();
        $kategori = WasteCategory::findOrFail($id);
        $kategori->update($request->validated());
        return redirect()->route('admin.kategori')->with('success', 'Kategori sampah berhasil diperbarui.');
    }

    public function kategoriDestroy($id)
    {
        $this->authorizeAdmin();
        $kategori = WasteCategory::findOrFail($id);
        // Proteksi sederhana jika kategori digunakan di transaksi
        if (\App\Models\TransactionDetail::where('waste_category_id', $kategori->id)->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena sudah ada transaksi terkait.');
        }
        $kategori->delete();
        return redirect()->route('admin.kategori')->with('success', 'Kategori sampah berhasil dihapus.');
    }

    // ==========================================
    // CRUD REWARD (KATALOG REWARD)
    // ==========================================

    public function reward()
    {
        $this->authorizeAdmin();
        $rewards = Reward::orderBy('created_at', 'desc')
            ->paginate(config('business.pagination_size', 10));
        return view('admin.reward.index', compact('rewards'));
    }

    public function rewardCreate()
    {
        $this->authorizeAdmin();
        return view('admin.reward.form');
    }

    public function rewardStore(StoreRewardRequest $request)
    {
        $this->authorizeAdmin();
        Reward::create($request->validated());
        return redirect()->route('admin.reward')->with('success', 'Reward berhasil ditambahkan.');
    }

    public function rewardEdit($id)
    {
        $this->authorizeAdmin();
        $reward = Reward::findOrFail($id);
        return view('admin.reward.form', compact('reward'));
    }

    public function rewardUpdate(UpdateRewardRequest $request, $id)
    {
        $this->authorizeAdmin();
        $reward = Reward::findOrFail($id);
        $reward->update($request->validated());
        return redirect()->route('admin.reward')->with('success', 'Reward berhasil diperbarui.');
    }

    public function rewardDestroy($id)
    {
        $this->authorizeAdmin();
        $reward = Reward::findOrFail($id);
        if (\App\Models\RedemptionDetail::where('reward_id', $reward->id)->exists()) {
            return back()->with('error', 'Reward tidak dapat dihapus karena sudah ada riwayat penukaran terkait.');
        }
        $reward->delete();
        return redirect()->route('admin.reward')->with('success', 'Reward berhasil dihapus.');
    }

    /**
     * Memastikan hanya role admin yang dapat memicu aksi master data (Defense-in-Depth).
     */
    private function authorizeAdmin(): void
    {
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses Ditolak: Anda tidak memiliki izin untuk halaman ini.');
    }
}
