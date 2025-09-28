<?php

namespace Database\Seeders;

use App\Models\Sku;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        Sku::Create([
            'product_id' => '1',
            'code' => 'SKU-20250925-220944514',
            'price' => '10.00',
            'inventory' => '10',
            'attributes' => [
                'color' => 'green',
                'size' => 'M',
                'material' => 'cotton',
            ],
        ]);
        Sku::Create([
            'product_id' => '1',
            'code' => 'SKU-20250925-220944515',
            'price' => '10.00',
            'inventory' => '10',
            'attributes' => [
                'color' => 'red',
                'size' => 'xs',
                'material' => 'cotton',
            ],
        ]);
        Sku::Create([
            'product_id' => '1',
            'code' => 'SKU-20250925-220944516',
            'price' => '10.00',
            'inventory' => '10',
            'attributes' => [
                'color' => 'blue',
                'size' => 'XL',
                'material' => 'cotton',
            ],
        ]);
    }
}