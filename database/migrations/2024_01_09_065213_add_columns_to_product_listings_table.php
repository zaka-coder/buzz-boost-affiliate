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
        Schema::table('product_listings', function (Blueprint $table) {
            $table->integer('relist_limit')->nullable();
            $table->foreignId('winner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('highest_bid_id')->nullable()->constrained('bids')->nullOnDelete();
            $table->boolean('reserved')->default(0);
            $table->boolean('sold')->default(0);
            $table->boolean('closed')->default(0);
            $table->dateTime('closed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_listings', function (Blueprint $table) {
            //
        });
    }
};
