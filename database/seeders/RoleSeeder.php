<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role; // jangan lupa import model Role

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menambahkan role default ke tabel roles
        Role::insert([
            ['name' => 'project_manager'],
            ['name' => 'finance_officer'],
            ['name' => 'project_director'],
            ['name' => 'staff'],
        ]);
    }
}
