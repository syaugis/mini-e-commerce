<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Polygon Monarch M3 26',
            'description' => 'Ada kegembiraan tersendiri ketika Rider ingin mempersembahkan sepeda gunung pertama untuk putra dan putri Rider. Kesempatan menikmati alam bersama sambil bersepeda merupakan salah satu momen bonding keluarga yang paling berharga. Untuk itu, putra-putri Rider berhak mendapatkan sepeda MTB yang tepat untuk mereka, yaitu Polygon Monarch M3.',
            'price' => 2200000,
            'stock' => 15,
            'category_id' => 1,
            'created_at' => now(),
        ]);
        Product::create([
            'name' => 'BARDI Smart LED Bluetooth 9W RGBWW',
            'description' => 'BARDI Smart LED Bluetooth 9W ini memiliki tingkat kecerahan hingga 806 lumens dan bisa di redupkan sesuai dengan keinginan Anda. Selain itu BARDI Smart LED Bluetooth 9W dilengkapi dengan kombinasi 16 juta warna RGB dan gradasi putih dari 2700k (warm white) hingga 6500k (cool white) sehingga warna lampu tidak monoton dan dapat diubah ubah sesusai dengan keinginan dan keadaan.
                              BARDI Smart LED Bluetooth 9W juga sudah dilengkapi dengan fitur preset suasana, yaitu pengaturan mode lampu yang dapat berubah terus menerus sesuai dengan pengaturan yang berlaku dan mode musik, yaitu warna BARDI Smart LED Bluetooth 9W akan mengikuti detakan musik yang diputar. Pemasangan BARDI Smart LED Bluetooth 9W ini juga sangat mudah di pasang, dikarenakan memiliki kepada ulir E27, yaitu fitting lampu paling umum di Indonesia.',
            'price' => 112000,
            'stock' => 100,
            'category_id' => 2,
            'created_at' => now(),
        ]);
    }
}
