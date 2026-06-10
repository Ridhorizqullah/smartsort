<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('admin_id')->constrained('users')->onDelete('restrict');
            $table->integer('total_point')->default(0);
            $table->string('idempotency_key')->unique();
            $table->timestamps();

            // Index tambahan untuk mengurutkan laporan setoran berdasarkan waktu
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
