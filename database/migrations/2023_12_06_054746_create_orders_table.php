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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('store_id')->constrained();
            $table->foreignId('shipping_provider_id')->nullable();
            $table->decimal('postal_insurance', 8, 2)->default(0);
            $table->decimal('shipping_cost', 8, 2)->default(0);
            $table->decimal('taxes', 8, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->string('currency')->default('USD');
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('pending');
            $table->string('transaction_id')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_country')->nullable();
            $table->string('shipping_postal_code')->nullable();
            $table->string('shipping_name')->nullable();
            $table->string('shipping_email')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->string('notes')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
