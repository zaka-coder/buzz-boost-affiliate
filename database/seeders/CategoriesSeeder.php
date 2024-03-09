<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CategoriesSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $categories = [
            [
                "name" => "Emerald",
            ],
            [
                "name" => "Ruby",
            ],
            [
                "name" => "Sapphire",
            ],
        ];

        // Create random categories
        // foreach (range(1, 2) as $index) {
        //     Category::create([
        //         'name' => ucfirst($faker->word),
        //     ]);
        // }

        // Create categories from list
        foreach ($categories as $category) {
            Category::create(
                [
                    'name' => $category['name'],
                ]
            );
        }


        // Get all created categories
        $categories = Category::all();

        // Create sub categories
        foreach ($categories as $category) {
            foreach (range(1, 2) as $index) {
                Category::create([
                    'name' => ucfirst($faker->word),
                    'parent_id' => $category->id,
                ]);
            }
        }

        // Get all created sub categories
        $subCategories = Category::whereNotNull('parent_id')->get();

        // Create sub sub categories
        foreach ($subCategories as $subCategory) {
            foreach (range(1, 2) as $index) {
                Category::create([
                    'name' => ucfirst($faker->word),
                    'parent_id' => $subCategory->id,
                ]);
            }
        }
    }
}
