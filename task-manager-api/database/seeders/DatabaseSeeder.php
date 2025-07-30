<?php

namespace Database\Seeders;

use App\Models\User; 

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 1 admin user
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // You can specify a password
        ]);

        // Create 1 regular user
        User::factory()->regularUser()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'), // You can specify a password
        ]);

        // Create 10 more random users (mix of admin/user roles)
        User::factory()->count(10)->create();
    }
}
