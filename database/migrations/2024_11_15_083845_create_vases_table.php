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
        Schema::create('vases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gypsum_product_id')->constrained('gypsum_products')->onDelete('cascade');
            $table->string('color')->nullable();
            $table->string('name');
            $table->decimal('gypsum_weight', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vases');
    }
};
