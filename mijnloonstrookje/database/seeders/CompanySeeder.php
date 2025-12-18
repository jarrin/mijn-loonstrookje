<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Company::create([
            'id' => 1,
            'name' => 'Acme BV',
            'kvk_number' => '12345678',
            'subscription_id' => 1,
        ]);

        \App\Models\Company::create([
            'id' => 2,
            'name' => 'Beta Solutions',
            'kvk_number' => '87654321',
            'subscription_id' => 2,
        ]);

        \App\Models\Company::create([
            'id' => 3,
            'name' => 'Gamma Industries',
            'kvk_number' => '11223344',
            'subscription_id' => 3,
        ]);
    }
}
