<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create 5 example users
        User::create([
            'username' => 'john_doe',
            'email' => 'john@example.com',
            'passwd' => Hash::make('password123'), // Hash password
            'picture' => null, // Add a path or leave null
            'name' => 'John Doe',
        ]);

        User::create([
            'username' => 'jane_doe',
            'email' => 'jane@example.com',
            'passwd' => Hash::make('password123'),
            'picture' => null,
            'name' => 'Jane Doe'
        ]);

        User::create([
            'username' => 'JellyFish',
            'email' => 'jellyfish@example.com',
            'passwd' => Hash::make('password123'),
            'picture' => null,
            'name' => 'Oliver Tan'
        ]);


        // You can create more users as needed
    }
}
