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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
            $table->string('name', 75);
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->string('video')->nullable();
            $table->double('weight')->nullable();
            $table->double('dim_length')->nullable();
            $table->double('dim_width')->nullable();
            $table->double('dim_depth')->nullable();
            $table->boolean('is_certified')->default(1);
            $table->string('treatment')->nullable();
            $table->string('shapes')->nullable();
            $table->string('types')->nullable();
            $table->string('clarity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
