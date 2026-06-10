<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('restrict');
            $table->integer('total_point');
            $table->enum('status', ['pending', 'approved', 'ready', 'completed', 'rejected'])->default('pending');
            $table->date('tanggal_ambil')->nullable();
            $table->string('idempotency_key')->unique();
            $table->text('catatan_admin')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            // Index tambahan untuk mengurutkan laporan penukaran berdasarkan waktu
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('redemptions');
    }
};
