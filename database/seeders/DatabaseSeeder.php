<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'admin',
            'email' => 'admin@eshop.com',
            'password' => bcrypt('password'),
        ]);
        User::create([
            'name' => 'user1',
            'email' => 'user1@eshop.com',
            'password' => bcrypt('password'),
        ]);
        User::create([
            'name' => 'user2',
            'email' => 'user2@eshop.com',
            'password' => bcrypt('password'),
        ]);
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}