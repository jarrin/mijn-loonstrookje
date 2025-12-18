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
        $this->call([
            SubscriptionSeeder::class,  // First create subscriptions
            CompanySeeder::class,       // Then create companies (which need subscriptions)
            UserSeeder::class,          // Then create users (which need companies)
            InvoiceSeeder::class,       // Finally create invoices
        ]);
    }
}
