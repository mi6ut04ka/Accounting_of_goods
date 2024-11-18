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
        Schema::create('container_candles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candle_id')->constrained('candles')->onDelete('cascade');
            $table->decimal('volume', 10, 2);
            $table->string('fragrance')->nullable();
            $table->decimal('fragrance_percentage', 5, 2)->nullable();
            $table->string('container_color')->nullable();
            $table->string('box_size')->nullable();
            $table->string('decor_description')->nullable();
            $table->enum('type_of_wax',['soy','coconut']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('container_candles');
    }
};
