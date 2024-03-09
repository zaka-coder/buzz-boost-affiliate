<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::create([
            'name' => 'Starter',
            'price' => 0,
            'auctions_items' => 2,
            'buyitnow_items' => 20,
        ]);
        Plan::create([
            'name' => 'Silver',
            'price' => 25,
            'auctions_items' => 10,
            'buyitnow_items' => 100,
        ]);
        Plan::create([
            'name' => 'Gold',
            'price' => 50,
            'auctions_items' => 20,
            'buyitnow_items' => 500,
        ]);
        Plan::create([
            'name' => 'Platinum',
            'price' => 75,
            'auctions_items' => 999999,
            'buyitnow_items' => 999999,
        ]);
    }
}
