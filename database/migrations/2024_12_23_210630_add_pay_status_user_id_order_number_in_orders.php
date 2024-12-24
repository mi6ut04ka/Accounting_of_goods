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
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('pay_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('order_number')->unique()->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('pay_status');
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropColumn('order_number');
        });
    }
};
