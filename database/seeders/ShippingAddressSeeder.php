<?php

namespace Database\Seeders;

use App\Models\ShippingAddress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShippingAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ShippingAddress::create([
            'user_id' => 2,
            'address' => 'Jalan Taman Jemur Sari No. 1',
            'city' => 'Surabaya',
            'postcode' => 60237,
            'phone' => '083947136098',
            'is_default' => 1,
            'created_at' => now(),
        ]);
        ShippingAddress::create([
            'user_id' => 3,
            'address' => 'Jalan Rungkut Asri No. 2',
            'city' => 'Surabaya',
            'postcode' => 60293,
            'phone' => '089427582591',
            'is_default' => 1,
            'created_at' => now(),
        ]);
    }
}
