<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Exception;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Menyimpan transaksi setoran sampah baru.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // 1. Validasi input setoran
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'admin_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.waste_category_id' => 'required|exists:waste_categories,id',
            'items.*.weight' => 'required|numeric|gt:0',
        ], [
            'user_id.required' => 'Warga penimbang wajib diisi.',
            'user_id.exists' => 'Warga tidak ditemukan.',
            'admin_id.required' => 'Petugas loket wajib diisi.',
            'admin_id.exists' => 'Petugas tidak ditemukan.',
            'items.required' => 'Item sampah wajib ditambahkan.',
            'items.array' => 'Format item sampah harus berupa list.',
            'items.min' => 'Minimal harus menyetor satu kategori sampah.',
            'items.*.waste_category_id.required' => 'Kategori sampah wajib diisi.',
            'items.*.waste_category_id.exists' => 'Kategori sampah tidak terdaftar.',
            'items.*.weight.required' => 'Berat sampah wajib diisi.',
            'items.*.weight.numeric' => 'Berat sampah harus berupa angka.',
            'items.*.weight.gt' => 'Berat sampah harus lebih besar dari 0.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi input gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // 2. Jalankan service pembuatan transaksi
            $transaction = $this->transactionService->createTransaction(
                $request->input('user_id'),
                $request->input('admin_id'),
                $request->input('items')
            );

            // 3. Kembalikan response sukses sesuai format standar
            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi setoran sampah berhasil disimpan.',
                'data' => [
                    'id' => $transaction->id,
                    'user_id' => $transaction->user_id,
                    'admin_id' => $transaction->admin_id,
                    'total_point' => $transaction->total_point,
                    'created_at' => $transaction->created_at->toDateTimeString(),
                    'details' => $transaction->details->map(function ($detail) {
                        return [
                            'waste_category_id' => $detail->waste_category_id,
                            'weight' => $detail->weight,
                            'price_snapshot' => $detail->price_snapshot,
                            'subtotal_point' => $detail->subtotal_point,
                        ];
                    }),
                ]
            ], 201);

        } catch (Exception $e) {
            // 4. Kembalikan response error jika terjadi kegagalan sistem/bisnis
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan transaksi setoran sampah: ' . $e->getMessage(),
                'data' => null
            ], 400);
        }
    }
}
