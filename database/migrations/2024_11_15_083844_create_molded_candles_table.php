<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('molded_candles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('candle_id')->constrained('candles')->onDelete('cascade');
            $table->decimal('wax_weight', 10, 2);
            $table->string('fragrance')->nullable();
            $table->decimal('fragrance_percentage', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('molded_candles');
    }
};
