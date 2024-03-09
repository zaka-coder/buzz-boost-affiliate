<?php

namespace Database\Seeders;

use App\Models\ListingTypes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ListingTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ListingTypes::create([
            'name' => 'Boost',
            'price' => 0.00,
            'duration' => 30,
        ]);
        ListingTypes::create([
            'name' => 'Premium',
            'price' => 15.00,
            'duration' => 7,
        ]);
        ListingTypes::create([
            'name' => 'Showcase',
            'price' => 30.00,
            'duration' => 7,
        ]);
        ListingTypes::create([
            'name' => 'Standard',
            'price' => 0.65,
            'duration' => 9999,
        ]);
    }
}
