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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Student1',
            'email' => 'student1@example.com',
            'password' => bcrypt('student1'),
            'role' => 'student'
        ]);

        User::factory()->create([
            'name' => 'teacher1',
            'email' => 'teacher1@example.com',
            'password' => bcrypt('teacher1'),
            'role' => 'teacher'
        ]);
    }
}
