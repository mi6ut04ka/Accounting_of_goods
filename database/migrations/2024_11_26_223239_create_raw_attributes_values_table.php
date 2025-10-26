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
        Schema::create('raw_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('raw_id');
            $table->unsignedBigInteger('attribute_id');
            $table->string('value');

            $table->foreign('raw_id')->references('id')->on('raws')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')->on('product_attributes')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_attributes_values');
    }
};
