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
        // Create some basic users first
        User::factory()->create([
            'name' => 'Test Student',
            'username' => 'test_student',
            'email' => 'student@example.com',
            'role' => 'student',
            'status' => 'approved',
        ]);

        User::factory()->create([
            'name' => 'Test Admin',
            'username' => 'test_admin',
            'email' => 'admin@buki.com', // This email domain allows admin access
            'role' => 'admin',
            'status' => 'approved',
        ]);

        // Run the course seeder
        $this->call([
            CourseSeeder::class,
        ]);
    }
}
