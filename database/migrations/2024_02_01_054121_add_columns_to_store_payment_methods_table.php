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
        Schema::table('store_payment_methods', function (Blueprint $table) {
            $table->longText('key')->nullable()->after('name');
            $table->longText('secret')->nullable()->after('key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_payment_methods', function (Blueprint $table) {
            $table->dropColumn('key');
            $table->dropColumn('secret');
        });
    }
};
