<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('redemption_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('redemption_id')->constrained('redemptions')->onDelete('cascade');
            $table->foreignId('reward_id')->constrained('rewards')->onDelete('restrict');
            $table->integer('qty');
            $table->integer('point_snapshot');
            $table->integer('subtotal_point');
            $table->timestamps();

            // Index tambahan untuk redemption_id
            $table->index('redemption_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('redemption_details');
    }
};
