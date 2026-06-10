<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRedemptionRequest;
use App\Models\Redemption;
use App\Models\Reward;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Services\RedemptionService;
use Illuminate\Support\Facades\Log;

class WargaController extends Controller
{

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

        return view('warga.dashboard', compact('user', 'totalTransaksi', 'totalRedemption', 'totalSampah'));
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
    public function transaksi()
    {
        $transactions = Transaction::with('details.wasteCategory')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(config('business.pagination_size', 10));

        return view('warga.transaksi', compact('transactions'));
    }

    /**
     * Riwayat penukaran poin milik warga.
     */
    public function redemption()
    {
        $redemptions = Redemption::with('details.reward')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(config('business.pagination_size', 10));

        return view('warga.redemption', compact('redemptions'));
    }

    /**
     * Ajukan penukaran poin ke sembako.
     * Validasi ditangani StoreRedemptionRequest (FormRequest).
     */
    public function storeRedemption(StoreRedemptionRequest $request, RedemptionService $redemptionService)
    {
        try {
            $items = [[
                'reward_id' => $request->reward_id,
                'qty'       => $request->qty,
            ]];

            $redemptionService->requestRedemption(
                auth()->id(),
                $items,
                $request->idempotency_key
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
}
