<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Jadikan kolom NIK NOT NULL.
     * NIK adalah primary login credential — tidak boleh NULL.
     *
     * Safety: set placeholder NIK bagi user lama yang mungkin NULL
     * sebelum mengubah constraint, agar migration tidak error.
     */
    public function up(): void
    {
        // Safety: ganti NULL dengan placeholder unik agar tidak gagal saat change()
        DB::statement("
            UPDATE users
            SET nik = CONCAT('UNKNOWN-', id)
            WHERE nik IS NULL
        ");

        Schema::table('users', function (Blueprint $table) {
            // Ubah kolom NIK: hapus nullable saja (unique index sudah ada dari migration awal)
            $table->string('nik')->nullable(false)->change();
        });
    }

    /**
     * Rollback: kembalikan NIK ke nullable jika perlu.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik')->nullable()->change();
        });
    }
};
