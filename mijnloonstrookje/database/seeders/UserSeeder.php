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

        // Create admin office (not linked to any company yet)
        $adminOffice = User::create([
            'name' => 'Administratiekantoor',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'administration_office',
        ]);

        User::create([
            'name' => 'Werkgever Acme',
            'email' => 'employer@acme.com',
            'password' => Hash::make('password'),
            'role' => 'employer',
            'company_id' => 1, // Acme BV
        ]);

        User::create([
            'name' => 'Werkgever Beta',
            'email' => 'employer@beta.com',
            'password' => Hash::make('password'),
            'role' => 'employer',
            'company_id' => 2, // Beta Solutions
        ]);

        User::create([
            'name' => 'Werkgever Test',
            'email' => 'employer@test.com',
            'password' => Hash::make('password'),
            'role' => 'employer',
            'company_id' => 1, 
        ]);

        User::create([
            'name' => 'Medewerker Test',
            'email' => 'employee@test.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'company_id' => 1, 
        ]);
    }
}
