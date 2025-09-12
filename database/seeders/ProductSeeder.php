<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        Product::create([
            'category_id' => $categories[6]->id,
            'name' => 'first floor lamp',
            'slug' => 'firstfloorlamps',
            'description' => 'first floor lamps for your home',
            'price' => '5.99',
        ]);
        Product::create([
            'category_id' => $categories[6]->id,
            'name' => 'second floor lamp',
            'slug' => 'secondfloorlamps',
            'description' => 'second floor lamps for your home',
            'price' => '5.99',
        ]);
        Product::create([
            'category_id' => $categories[6]->id,
            'name' => 'third floor lamp',
            'slug' => 'thirdfloorlamps',
            'description' => 'third floor lamps for your home',
            'price' => '5.99',
        ]);
        Product::create([
            'category_id' => $categories[2]->id,
            'name' => 'first Metal desk lamp',
            'slug' => 'firstmetaldesklamp',
            'description' => 'first metal desk lamps for your office',
            'price' => '5.99',

        ]);
        Product::create([
            'category_id' => $categories[3]->id,
            'name' => 'second Wooden desk lamp',
            'slug' => 'secondwoodendesklamp',
            'description' => 'second wooden desk lamps for your office',
            'price' => '9.99',

        ]);
    }
}