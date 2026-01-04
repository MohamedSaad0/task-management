<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Manager User',
            'email' => 'manager@demo.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);

        User::create([
            'name' => 'Employee User',
            'email' => 'user@demo.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Employee User 2',
            'email' => 'user2@demo.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}
