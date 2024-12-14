<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('notadmin'),
            'role' => 0,
        ]);

        User::create([
            'name' => 'user 1',
            'email' => 'user1@gmail.com',
            'password' => Hash::make('notadmin'),
            'role' => 1,
        ]);

        User::create([
            'name' => 'user 2',
            'email' => 'user2@gmail.com',
            'password' => Hash::make('notadmin'),
            'role' => 1,
        ]);
    }
}
