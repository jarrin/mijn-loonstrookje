<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\Company;
use App\Models\User;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get subscriptions to assign to companies
        $subscriptions = Subscription::all();
        
        // Create some demo companies with users
        $companiesData = [
            ['name' => 'Acme BV', 'kvk_number' => '12345678', 'email' => 'employer@acme.com'],
            ['name' => 'Beta Solutions', 'kvk_number' => '87654321', 'email' => 'employer@beta.com'],
            ['name' => 'Gamma Industries', 'kvk_number' => '11223344', 'email' => 'employer@gamma.com'],
        ];

        $companies = [];
        foreach ($companiesData as $index => $companyData) {
            // Assign subscriptions in a round-robin fashion
            $subscription = $subscriptions[$index % $subscriptions->count()];
            
            $company = Company::create([
                'name' => $companyData['name'],
                'kvk_number' => $companyData['kvk_number'],
                'subscription_id' => $subscription->id
            ]);

            // Create employer user for this company
            User::create([
                'name' => $companyData['name'] . ' Werkgever',
                'email' => $companyData['email'],
                'password' => Hash::make('password'),
                'role' => 'employer',
                'company_id' => $company->id,
            ]);

            $companies[] = $company;
        }

        // Create demo invoices for each company
        foreach ($companies as $index => $company) {
            Invoice::create([
                'company_id' => $company->id,
                'mollie_invoice_id' => 'demo_' . ($index + 1) . '_1',
                'amount' => 99.99 + ($index * 10),
                'status' => 'paid',
                'due_date' => Carbon::now()->subDays(10 + $index)->toDateString(),
                'paid_at' => Carbon::now()->subDays(9 + $index)->toDateTimeString(),
            ]);

            Invoice::create([
                'company_id' => $company->id,
                'mollie_invoice_id' => 'demo_' . ($index + 1) . '_2',
                'amount' => 49.50 + ($index * 5),
                'status' => $index % 2 === 0 ? 'pending' : 'overdue',
                'due_date' => Carbon::now()->addDays(5 + $index)->toDateString(),
                'paid_at' => null,
            ]);
        }
    }
}
