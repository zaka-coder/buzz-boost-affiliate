<?php

namespace Database\Seeders;

use App\Models\ShippingProvider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingProvidersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // delete all records in table with foreign key constraint
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('shipping_providers')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // insert records
        ShippingProvider::create(['name' => 'FedEX']);
        ShippingProvider::create(['name' => 'DHL']);
        ShippingProvider::create(['name' => 'Express Shipping']);
        ShippingProvider::create(['name' => 'Registered Shipping']);
        ShippingProvider::create(['name' => 'Standard Shipping']);
    }
}

