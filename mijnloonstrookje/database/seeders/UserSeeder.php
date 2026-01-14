<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or find test company
        $company = Company::firstOrCreate(
            ['kvk_number' => '12345678'],
            ['name' => 'Test Bedrijf BV']
        );

        // Create test users for each role
        User::updateOrCreate(
            ['email' => 'superadmin@test.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Administratiekantoor',
                'password' => Hash::make('password'),
                'role' => 'administration_office',
                'company_id' => null, // Admin offices don't have a direct company_id
            ]
        );
        
        // Link admin office to test company
        $adminOffice = User::where('email', 'admin@test.com')->first();
        if ($adminOffice && !$company->adminOffices()->where('admin_office_id', $adminOffice->id)->exists()) {
            $company->adminOffices()->attach($adminOffice->id, [
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        User::updateOrCreate(
            ['email' => 'employer@test.com'],
            [
                'name' => 'Werkgever Test',
                'password' => Hash::make('password'),
                'role' => 'employer',
                'company_id' => $company->id,
            ]
        );

        User::updateOrCreate(
            ['email' => 'employee@test.com'],
            [
                'name' => 'Medewerker Test',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'company_id' => $company->id,
            ]
        );
    }
}
