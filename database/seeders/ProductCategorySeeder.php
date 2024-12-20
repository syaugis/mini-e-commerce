<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductCategory::create([
            'name' => 'Bicycle',
            'created_at' => now(),
        ]);
        ProductCategory::create([
            'name' => 'Lamp',
            'created_at' => now(),
        ]);
    }
}
