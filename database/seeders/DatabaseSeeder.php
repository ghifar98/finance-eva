<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan RoleSeeder dulu
        $this->call([
            RoleSeeder::class,
        ]);

        // User awal
        User::factory()->create([
            'name' => 'Project Manager',
            'email' => 'pm@example.com',
            'role_id' => 1, // project_manager
            'password' => bcrypt('password123'),
        ]);

        User::factory()->create([
            'name' => 'Finance Officer',
            'email' => 'finance@example.com',
            'role_id' => 2, // finance_officer
            'password' => bcrypt('password123'),
        ]);

        User::factory()->create([
            'name' => 'Project Director',
            'email' => 'director@example.com',
            'role_id' => 3, // project_director
            'password' => bcrypt('password123'),
        ]);

        User::factory()->create([
            'name' => 'Staff',
            'email' => 'staff@example.com',
            'role_id' => 4, // staff
            'password' => bcrypt('password123'),
        ]);
    }
}
