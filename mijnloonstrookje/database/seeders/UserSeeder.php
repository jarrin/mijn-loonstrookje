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
            [
                'name' => 'Test Bedrijf BV',
                'subscription_id' => 1, // Assign basic plan
            ]
        );
        
        // Ensure company has subscription
        if (!$company->subscription_id) {
            $company->subscription_id = 1;
            $company->save();
        }

        // Create test users for each role
        User::updateOrCreate(
            ['email' => 'superadmin@test.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
                'two_factor_secret' => null,
                'two_factor_confirmed_at' => null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Administratiekantoor',
                'password' => Hash::make('password'),
                'role' => 'administration_office',
                'company_id' => null,
                'email_verified_at' => now(),
                'two_factor_secret' => null,
                'two_factor_confirmed_at' => null,
            ]
        );
        
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
                'email_verified_at' => now(),
                'two_factor_secret' => null,
                'two_factor_confirmed_at' => null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'employee@test.com'],
            [
                'name' => 'Medewerker Test',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'company_id' => $company->id,
                'email_verified_at' => now(),
                'two_factor_secret' => null,
                'two_factor_confirmed_at' => null,
            ]
        );
    }
}
