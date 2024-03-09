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
        Schema::create('shipping_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->text('shipping_terms')->nullable();
            $table->decimal('insurance')->nullable();
            $table->decimal('domestic_shipping_fee_per_item')->nullable();
            $table->string('domestic_transit_time')->nullable();
            $table->enum('combine_shipping', ['yes', 'no'])->default('no')->nullable();
            $table->decimal('domestic_bulk_discount_rate')->nullable();
            $table->integer('minimum_order_quantity')->nullable();
            $table->foreignId('shipping_provider_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_preferences');
    }
};
