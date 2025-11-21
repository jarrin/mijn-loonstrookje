<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\Company;
use Carbon\Carbon;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some demo companies
        $companies = [
            Company::create(['name' => 'Acme BV', 'kvk_number' => '12345678', 'subscription_id' => null]),
            Company::create(['name' => 'Beta Solutions', 'kvk_number' => '87654321', 'subscription_id' => null]),
            Company::create(['name' => 'Gamma Industries', 'kvk_number' => '11223344', 'subscription_id' => null]),
        ];

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
