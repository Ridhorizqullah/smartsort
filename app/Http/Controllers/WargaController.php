<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Redemption;
use App\Models\Reward;
use Illuminate\Support\Facades\Log;

class WargaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:warga']);
    }
    /**
     * Tampilan Beranda Dashboard Warga
     */
    public function dashboard()
    {
        $user = auth()->user();
        
        $totalTransaksi = Transaction::where('user_id', $user->id)->count();
        $totalRedemption = Redemption::where('user_id', $user->id)->count();
        $totalSampah = \App\Models\TransactionDetail::join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->where('transactions.user_id', $user->id)
            ->sum('transaction_details.weight');

        return view('warga.dashboard', compact('user', 'totalTransaksi', 'totalRedemption', 'totalSampah'));
    }

    /**
     * Tampilan Katalog Sembako
     */
    public function katalog()
    {
        $rewards = Reward::where('stock', '>', 0)
            ->orderBy('name')
            ->paginate(12);
        return view('warga.katalog', compact('rewards'));
    }

    /**
     * Tampilan Riwayat Transaksi (Setor Sampah)
     */
    public function transaksi()
    {
        $transactions = Transaction::with('details.category')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('warga.transaksi', compact('transactions'));
    }

    /**
     * Tampilan Riwayat Penukaran (Redemption)
     */
    public function redemption()
    {
        $redemptions = Redemption::with('details.reward')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('warga.redemption', compact('redemptions'));
    }

    /**
     * Proses pengajuan penukaran poin ke sembako.
     */
    public function storeRedemption(Request $request, \App\Services\RedemptionService $redemptionService)
    {
        $request->validate([
            'reward_id' => 'required|exists:rewards,id',
            'qty' => 'required|integer|min:1|max:99',
            'idempotency_key' => ['required', 'string', 'uuid'],
        ]);

        try {
            // Kita bungkus sebagai array items karena service layer menerima array multi-item
            $items = [
                [
                    'reward_id' => $request->reward_id,
                    'qty' => $request->qty,
                ]
            ];

            $redemption = $redemptionService->requestRedemption(
                auth()->id(), 
                $items, 
                $request->idempotency_key
            );

            return redirect()->route('warga.redemption')->with('success', 'Permintaan penukaran berhasil diajukan!');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('QueryException on storeRedemption', ['user_id' => auth()->id(), 'msg' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
