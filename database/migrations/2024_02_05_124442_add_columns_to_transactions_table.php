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
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable();
            $table->foreignId('product_id')->nullable();
            $table->foreignId('store_id')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('order_id');
            $table->dropColumn('product_id');
            $table->dropColumn('store_id');
            $table->dropColumn('amount');
        });
    }
};
