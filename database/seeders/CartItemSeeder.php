<?php

namespace Database\Seeders;

use App\Models\CartItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CartItem::create([
            'cart_id' => 1,
            'product_id' => 2,
            'quantity' => 3,
        ]);
        CartItem::create([
            'cart_id' => 2,
            'product_id' => 2,
            'quantity' => 5,
        ]);
    }
}
