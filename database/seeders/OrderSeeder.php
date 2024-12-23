<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::create([
            'user_id' => 2,
            'order_id' => "ORD-1734786069-6766BC154493E",
            'shipping_address_id' => 1,
            'status' => 3,
            'total_price' => 224000,
            'created_at' => now(),
        ]);
        Order::create([
            'user_id' => 3,
            'order_id' => "ORD-1831234569-6766BC154493F",
            'shipping_address_id' => 2,
            'status' => 3,
            'total_price' => 2200000,
            'created_at' => now(),
        ]);
    }
}
