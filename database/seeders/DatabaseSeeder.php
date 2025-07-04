<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\ModuleSchedule;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Trainee',
            'email' => 'trainee1@example.com',
            'password' => Hash::make('password'),
        ]);

        // Create a trainer user
        User::create([
            'name' => 'Trainer User',
            'email' => 'trainer1@test.com',
            'password' => Hash::make('password'),
            'role' => 'trainer',
        ]);


        // Create an admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);


    }
}
