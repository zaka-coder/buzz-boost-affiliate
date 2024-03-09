<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // call other seeders here
        $this->call([
            RolesSeeder::class,
            ShippingProvidersSeeder::class,
            CategoriesSeeder::class,
            UsersSeeder::class,
        ]);

        // // create user
        // $user = User::factory()->create([
        //     'name' => 'User',
        //     'email' => 'user@gmail.com',
        //     'password' => bcrypt('admin123'),
        // ]);
        // // assign role
        // $user->assignRole('buyer');

        // // create admin
        // $admin = User::factory()->create([
        //     'name' => 'Admin',
        //     'email' => 'admin@gmail.com',
        //     'password' => bcrypt('admin123'),
        // ]);
        // // assign role
        // $admin->assignRole('admin');
    }
}
