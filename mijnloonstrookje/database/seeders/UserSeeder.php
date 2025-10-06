<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test users for each role
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@test.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        User::create([
            'name' => 'Administratiekantoor Manager',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'administration_office',
        ]);

        User::create([
            'name' => 'Werkgever Test',
            'email' => 'employer@test.com',
            'password' => Hash::make('password'),
            'role' => 'employer',
        ]);

        User::create([
            'name' => 'Medewerker Test',
            'email' => 'employee@test.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
        ]);
    }
}
