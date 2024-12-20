<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderItem::create([
            'order_id' => 1,
            'product_id' => 2,
            'product_name' => 'BARDI Smart LED Bluetooth 9W RGBWW',
            'product_price' => 112000,
            'quantity' => 2,
            'created_at' => now(),
        ]);
        OrderItem::create([
            'order_id' => 2,
            'product_id' => 1,
            'product_name' => 'Polygon Monarch M3 26',
            'product_price' => 2200000,
            'quantity' => 1,
            'created_at' => now(),
        ]);
    }
}
