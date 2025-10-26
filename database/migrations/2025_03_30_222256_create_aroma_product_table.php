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
        Schema::create('aroma_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aroma_id')->constrained('aromas')->onDelete('cascade');;
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');;
            $table->integer('in_stock')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aroma_product');
    }
};
