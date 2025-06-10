<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Trainee;

class TraineeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    Trainee::create(['name' => 'John Doe', 'email' => 'john@example.com']);
    Trainee::create(['name' => 'Alice Smith', 'email' => 'alice@example.com']);
}
}
