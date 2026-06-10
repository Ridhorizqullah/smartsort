<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('point_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->enum('type', ['credit', 'debit']);
            $table->integer('amount');
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->onDelete('set null');
            $table->foreignId('redemption_id')->nullable()->constrained('redemptions')->onDelete('set null');
            $table->string('description')->nullable();
            $table->timestamps();

            // Index
            $table->index(['user_id', 'type']);
            $table->index('transaction_id');
            $table->index('redemption_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_ledgers');
    }
};
