<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $batchSize = 1000;
        $totalRecords = 100000;
        $data = [];

        for ($i = 1; $i <= $totalRecords; $i++) {
            $data[] = [
                'name' => $faker->words(3, true),
                'description' => $faker->paragraph,
                'price' => $faker->numberBetween(100000, 5000000),
                'stock' => $faker->numberBetween(1, 100),
                'category_id' => $faker->numberBetween(1, 2),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($i % $batchSize === 0) {
                Product::insert($data);
                $data = [];
            }
        }

        if (!empty($data)) {
            Product::insert($data);
        }
    }
}
