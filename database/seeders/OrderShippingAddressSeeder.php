<?php

namespace Database\Seeders;

use App\Models\OrderShippingAddress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderShippingAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderShippingAddress::create([
            'order_id' => 1,
            'address' => 'Jalan Taman Jemur Sari No. 1',
            'city' => 'Surabaya',
            'postcode' => 60237,
            'phone' => '083947136098',
            'created_at' => now(),
        ]);
        OrderShippingAddress::create([
            'order_id' => 2,
            'address' => 'Jalan Rungkut Asri No. 2',
            'city' => 'Surabaya',
            'postcode' => 60293,
            'phone' => '089427582591',
            'created_at' => now(),
        ]);
    }
}
