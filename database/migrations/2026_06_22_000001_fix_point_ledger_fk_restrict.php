<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Perbaikan integritas referential constraint point_ledgers.
 *
 * MASALAH: Migration awal menggunakan onDelete('set null') untuk transaction_id
 * dan redemption_id. Ini berarti jika transaksi/redemption dihapus, entry di
 * point_ledgers kehilangan referencenya (jadi NULL) — ledger menjadi "orphan"
 * dan TIDAK BISA direkonsiliasi ke transaksi aslinya.
 *
 * SOLUSI: Ubah ke onDelete('restrict') — transaksi/redemption TIDAK BISA dihapus
 * selama masih ada ledger yang mereferensikannya. Ini memaksa immutability data
 * finansial secara penuh di level database.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('point_ledgers', function (Blueprint $table) {
            // Hapus foreign key lama
            $table->dropForeign(['transaction_id']);
            $table->dropForeign(['redemption_id']);

            // Buat ulang dengan onDelete('restrict') agar ledger tidak bisa di-orphan
            $table->foreign('transaction_id')
                ->references('id')->on('transactions')
                ->onDelete('restrict');

            $table->foreign('redemption_id')
                ->references('id')->on('redemptions')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('point_ledgers', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
            $table->dropForeign(['redemption_id']);

            $table->foreign('transaction_id')
                ->references('id')->on('transactions')
                ->onDelete('set null');

            $table->foreign('redemption_id')
                ->references('id')->on('redemptions')
                ->onDelete('set null');
        });
    }
};
