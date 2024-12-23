<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Payment::create([
            'order_id' => 1,
            'snap_token' => "12b1b7b7-7b1b-4b1b-8b1b-1b7b1b7b1b7b",
            'status' => "paid",
            'paid_at' => now(),
        ]);
        Payment::create([
            'order_id' => 2,
            'snap_token' => "14b1b7b7-7b1b-4b1b-8b1b-1b7b1b7b1b7b",
            'status' => "paid",
            'paid_at' => now(),
        ]);
    }
}
