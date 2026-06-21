<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRedemptionRequest;
use App\Models\Redemption;
use App\Models\Reward;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Services\RedemptionService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WargaController extends Controller
{
    /**
     * Constructor Controller.
     * Note: Middleware auth dan role:warga dikonfigurasi pada route level (routes/warga.php).
     */
    public function __construct()
    {
        // Constructor dipertahankan tetap aman
    }

    /**
     * Beranda Dashboard Warga: statistik saldo dan aktivitas.
     */
    public function dashboard()
    {
        $user = auth()->user();

        $totalTransaksi  = Transaction::where('user_id', $user->id)->count();
        $totalRedemption = Redemption::where('user_id', $user->id)->count();
        $totalSampah     = TransactionDetail::join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->where('transactions.user_id', $user->id)
            ->sum('transaction_details.weight');

        // Total poin yang sudah benar-benar dipotong (hanya status approved, ready, completed)
        $totalPoinDipakai = Redemption::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'ready', 'completed'])
            ->sum('total_point');

        // Filter and Search for Redemptions
        $query = Redemption::with('details.reward')->where('user_id', $user->id);

        $statusFilter = $this->whitelist((string)request()->input('status'), ['', 'pending', 'approved', 'ready', 'completed', 'rejected']);
        if ($statusFilter !== '') {
            $query->where('status', $statusFilter);
        }

        $sortDir = request()->input('sort') === 'oldest' ? 'asc' : 'desc';
        $query->orderBy('created_at', $sortDir);

        $dashboardRedemptions = $query->paginate(config('business.pagination_size', 5))->withQueryString();

        // Setor sampah terakhir
        $latestTransaction = Transaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        // 1. Query recommended rewards (affordable, ordered by highest points first, max 3 items)
        $recommendedRewards = Reward::where('stock', '>', 0)
            ->where('point_cost', '<=', $user->saldo_poin)
            ->orderBy('point_cost', 'desc')
            ->take(3)
            ->get();

        // 2. Query recent transactions for timeline
        $recentTransactions = Transaction::with('details.wasteCategory')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($t) {
                $catNames = $t->details->map(fn($d) => ($d->wasteCategory->name ?? 'Sampah'))->unique()->implode(', ');
                return [
                    'type' => 'setor',
                    'points' => $t->total_point,
                    'weight' => $t->details->sum('weight'),
                    'created_at' => $t->created_at,
                    'description' => "Setor sampah (" . $catNames . ")"
                ];
            });

        // 3. Query recent redemptions for timeline
        $recentRedemptions = Redemption::with('details.reward')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($r) {
                $items = $r->details->map(fn($d) => $d->qty . "x " . ($d->reward->name ?? 'Reward'))->implode(', ');
                return [
                    'type' => 'tukar',
                    'points' => $r->total_point,
                    'created_at' => $r->created_at,
                    'description' => "Tukar " . $items
                ];
            });

        // 4. Combine both and sort by newest
        $timeline = $recentTransactions->concat($recentRedemptions)
            ->sortByDesc('created_at')
            ->take(5);

        return view('warga.dashboard', compact(
            'user', 'totalTransaksi', 'totalRedemption', 'totalSampah', 
            'totalPoinDipakai', 'dashboardRedemptions', 'latestTransaction',
            'recommendedRewards', 'timeline'
        ));
    }

    /**
     * Endpoint API untuk Warga Dashboard (Realtime Fetch).
     */
    public function apiStatus()
    {
        $user = auth()->user();

        $latestRedemptions = Redemption::with('details.reward')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($redemption) {
                return [
                    'id' => str_pad($redemption->id, 5, '0', STR_PAD_LEFT),
                    'raw_id' => $redemption->id,
                    'status' => $redemption->status,
                    'item_name' => $redemption->details->first()->reward->name ?? 'Sembako',
                    'additional_items_count' => $redemption->details->count() > 1 ? $redemption->details->count() - 1 : 0,
                    'tanggal_ambil' => $redemption->tanggal_ambil ? \Carbon\Carbon::parse($redemption->tanggal_ambil)->translatedFormat('d F Y') : null,
                ];
            });

        return response()->json([
            'saldo' => $user->saldo_poin,
            'redemptions' => $latestRedemptions
        ]);
    }

    /**
     * Katalog sembako yang tersedia untuk penukaran.
     */
    public function katalog()
    {
        $rewards = Reward::where('stock', '>', 0)
            ->orderBy('name')
            ->paginate(config('business.pagination_size', 12));

        return view('warga.katalog', compact('rewards'));
    }

    /**
     * Riwayat setoran sampah milik warga.
     */
    public function transaksi(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();

        // 1. Hitung Statistik Ringkas (Keseluruhan/Lifetime)
        $totalSetoran = Transaction::where('user_id', $user->id)->count();
        $totalBerat = TransactionDetail::join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->where('transactions.user_id', $user->id)
            ->sum('transaction_details.weight');
        $totalPoin = Transaction::where('user_id', $user->id)->sum('total_point');

        // 2. Query dengan Eager Load dan Filter
        $query = Transaction::with('details.wasteCategory')
            ->where('user_id', $user->id);

        // Filter Waktu (Whitelisted & Type-Safe)
        $waktuFilter = $this->whitelist((string)$request->input('waktu'), ['', 'hari_ini', 'minggu_ini', 'bulan_ini', 'bulan_lalu', 'tahun_ini']);
        if ($waktuFilter !== '') {
            $this->applyTimeFilter($query, $waktuFilter, 'created_at');
        }

        // Filter Search (Sanitasi String & Wildcard Escape)
        if ($request->filled('search')) {
            $search = $this->sanitizeSearch((string)$request->input('search'), 50);
            if ($search !== '') {
                $searchId = ltrim($search, '#');
                $searchId = ltrim($searchId, '0');
                
                $query->where(function($q) use ($search, $searchId) {
                    if (is_numeric($searchId) && $searchId !== '') {
                        $q->where('id', $searchId);
                    }
                    $q->orWhereHas('details.wasteCategory', function($subQuery) use ($search) {
                        $subQuery->where('name', 'like', '%' . $search . '%');
                    });
                });
            }
        }

        $transactions = $query->orderBy('created_at', 'desc')
            ->paginate(config('business.pagination_size', 10))
            ->withQueryString();

        return view('warga.transaksi', compact(
            'transactions', 'totalSetoran', 'totalBerat', 'totalPoin'
        ));
    }

    /**
     * Riwayat penukaran poin milik warga.
     */
    public function redemption(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();

        // 1. Hitung Statistik Ringkas (Keseluruhan/Lifetime)
        $totalPenukaran = Redemption::where('user_id', $user->id)->count();
        $totalPoinDipakai = Redemption::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'ready', 'completed'])
            ->sum('total_point');
        $penukaranPending = Redemption::where('user_id', $user->id)->where('status', 'pending')->count();
        $penukaranSelesai = Redemption::where('user_id', $user->id)->where('status', 'completed')->count();

        // 2. Query dengan Eager Load dan Filter
        $query = Redemption::with('details.reward')
            ->where('user_id', $user->id);

        // Filter Waktu (Whitelisted & Type-Safe)
        $waktuFilter = $this->whitelist((string)$request->input('waktu'), ['', 'hari_ini', 'minggu_ini', 'bulan_ini', 'bulan_lalu', 'tahun_ini']);
        if ($waktuFilter !== '') {
            $this->applyTimeFilter($query, $waktuFilter, 'created_at');
        }

        // Filter Status (Whitelisted)
        $statusFilter = $this->whitelist((string)$request->input('status'), ['', 'pending', 'approved', 'ready', 'completed', 'rejected']);
        if ($statusFilter !== '') {
            $query->where('status', $statusFilter);
        }

        $redemptions = $query->orderBy('created_at', 'desc')
            ->paginate(config('business.pagination_size', 10))
            ->withQueryString();

        return view('warga.redemption', compact(
            'redemptions', 'totalPenukaran', 'totalPoinDipakai', 'penukaranPending', 'penukaranSelesai'
        ));
    }

    /**
     * Ajukan penukaran poin ke sembako.
     * Validasi ditangani StoreRedemptionRequest (FormRequest).
     */
    public function storeRedemption(StoreRedemptionRequest $request, RedemptionService $redemptionService)
    {
        try {
            // Hardening: Validasi pola dan panjang pada idempotency_key (alphanumeric & hyphen, max 50 karakter)
            $idempotencyKey = (string) $request->input('idempotency_key');
            if (strlen($idempotencyKey) > 50 || !preg_match('/^[a-zA-Z0-9\-]+$/', $idempotencyKey)) {
                return back()->with('error', 'Idempotency key tidak valid.');
            }

            $items = [[
                'reward_id' => $request->reward_id,
                'qty'       => $request->qty,
            ]];

            $redemptionService->requestRedemption(
                auth()->id(),
                $items,
                $idempotencyKey
            );

            return redirect()->route('warga.redemption')
                ->with('success', 'Permintaan penukaran berhasil diajukan!');

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('QueryException on storeRedemption', [
                'user_id' => auth()->id(),
                'msg'     => $e->getMessage(),
            ]);
            return back()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Sanitasi pencarian: trim, batasi panjang, dan hapus wildcard SQL.
     */
    private function sanitizeSearch(string $value, int $maxLength = 50): string
    {
        $val = trim($value);
        $val = mb_substr($val, 0, $maxLength);
        return str_replace(['%', '_'], '', $val);
    }

    /**
     * Whitelist filter: batasi nilai input sesuai daftar nilai yang diperbolehkan.
     */
    private function whitelist(string $value, array $allowed): string
    {
        $val = trim($value);
        if (in_array($val, $allowed, true)) {
            return $val;
        }
        return ''; // Default/Empty
    }

    /**
     * Terapkan filter waktu Carbon pada query.
     */
    private function applyTimeFilter($query, string $filter, string $column = 'created_at')
    {
        switch ($filter) {
            case 'hari_ini':
                $query->whereDate($column, today());
                break;
            case 'minggu_ini':
                $query->whereBetween($column, [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'bulan_ini':
                $query->whereMonth($column, now()->month)
                      ->whereYear($column, now()->year);
                break;
            case 'bulan_lalu':
                $query->whereMonth($column, now()->subMonth()->month)
                      ->whereYear($column, now()->subMonth()->year);
                break;
            case 'tahun_ini':
                $query->whereYear($column, now()->year);
                break;
        }
        return $query;
    }
}
