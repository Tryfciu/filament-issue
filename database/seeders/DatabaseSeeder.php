<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        for ($i = 0; $i < 2000; $i++) {
            User::factory()->create([
                'name' => 'Test User ' . $i,
                'email' => 'test' . $i . '@example.com',
            ]);
        }
    }
}
