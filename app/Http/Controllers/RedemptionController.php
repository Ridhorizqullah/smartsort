<?php

namespace App\Http\Controllers;

use App\Services\RedemptionService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Exception;

class RedemptionController extends Controller
{
    protected $redemptionService;

    public function __construct(RedemptionService $redemptionService)
    {
        $this->redemptionService = $redemptionService;
    }

    /**
     * Menyimpan transaksi penukaran sembako baru.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // 1. Validasi input penukaran
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'admin_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.reward_id' => 'required|exists:rewards,id',
            'items.*.qty' => 'required|integer|gt:0',
        ], [
            'user_id.required' => 'Warga penukar wajib diisi.',
            'user_id.exists' => 'Warga tidak ditemukan.',
            'admin_id.required' => 'Petugas loket wajib diisi.',
            'admin_id.exists' => 'Petugas tidak ditemukan.',
            'items.required' => 'Item sembako wajib ditambahkan.',
            'items.array' => 'Format item sembako harus berupa list.',
            'items.min' => 'Minimal harus menukar satu item barang.',
            'items.*.reward_id.required' => 'Item reward wajib diisi.',
            'items.*.reward_id.exists' => 'Item sembako tidak terdaftar.',
            'items.*.qty.required' => 'Kuantitas barang wajib diisi.',
            'items.*.qty.integer' => 'Kuantitas barang harus berupa angka bulat.',
            'items.*.qty.gt' => 'Kuantitas barang harus lebih besar dari 0.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi input gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // 2. Jalankan service pembuatan transaksi penukaran
            $redemption = $this->redemptionService->createRedemption(
                $request->input('user_id'),
                $request->input('admin_id'),
                $request->input('items')
            );

            // 3. Kembalikan response sukses sesuai format standar
            return response()->json([
                'status' => 'success',
                'message' => 'Penukaran sembako berhasil diproses.',
                'data' => [
                    'id' => $redemption->id,
                    'user_id' => $redemption->user_id,
                    'admin_id' => $redemption->admin_id,
                    'total_point' => $redemption->total_point,
                    'status' => $redemption->status,
                    'created_at' => $redemption->created_at->toDateTimeString(),
                    'details' => $redemption->details->map(function ($detail) {
                        return [
                            'reward_id' => $detail->reward_id,
                            'qty' => $detail->qty,
                            'point_snapshot' => $detail->point_snapshot,
                            'subtotal_point' => $detail->subtotal_point,
                        ];
                    }),
                ]
            ], 201);

        } catch (Exception $e) {
            // 4. Kembalikan response error jika terjadi kegagalan sistem/bisnis (misal: saldo/stok kurang)
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memproses penukaran sembako: ' . $e->getMessage(),
                'data' => null
            ], 400);
        }
    }
}
