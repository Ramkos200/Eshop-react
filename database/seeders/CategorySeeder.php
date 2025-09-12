<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Category::create([

            'name' => 'Floor Lamps',
            'slug' => 'floor-lamps',
            'description' => 'floor lamps for your home',
            'parent_id' => null,

        ]);
        Category::create([
            'name' => 'Desk Lamps',
            'slug' => 'desk-lamps',
            'description' => 'desk lamps for your office',
            'parent_id' => null,

        ]);
        $categories = Category::all();
        Category::create([
            'name' => 'Metal Desk Lamps',
            'slug' => 'metal-desk-lamps',
            'description' => 'Metal desk lamps for your office',
            'parent_id' => $categories[1]->id

        ]);
        Category::create([
            'name' => 'Wooden Desk Lamps',
            'slug' => 'wooden-desk-lamps',
            'description' => 'Wooden desk lamps for your office',
            'parent_id' => $categories[1]->id

        ]);
        Category::create([
            'name' => 'Plastic Floor Lamps',
            'slug' => 'plastic-floor-lamps',
            'description' => 'Plastic floor lamps for your home',
            'parent_id' => $categories[0]->id

        ]);
        Category::create([
            'name' => 'Metal FLoor Lamps',
            'slug' => 'metal-floor-lamps',
            'description' => 'Metal floor lamps for your home',
            'parent_id' => $categories[0]->id

        ]);
        Category::create([
            'name' => 'Wooden Floor Lamps',
            'slug' => 'wooden-floor-lamps',
            'description' => 'Wooden floor lamps for your home',
            'parent_id' => $categories[0]->id

        ]);
        Category::create([
            'name' => 'Glass Floor Lamps',
            'slug' => 'glass-floor-lamps',
            'description' => 'Glass floor lamps for your home',
            'parent_id' => $categories[0]->id

        ]);
    }
}