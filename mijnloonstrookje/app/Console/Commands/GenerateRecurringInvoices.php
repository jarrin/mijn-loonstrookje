<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Subscription;
use App\Models\CustomSubscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceCreated;

class GenerateRecurringInvoices extends Command
{
    protected $signature = 'invoices:generate-recurring';
    protected $description = 'Genereer periodieke facturen voor actieve abonnementen';

    public function handle()
    {
        $today = Carbon::today();
        $count = 0;

        // Standaard abonnementen
        $companies = Company::whereNotNull('subscription_id')->get();
        foreach ($companies as $company) {
            $subscription = $company->subscription;
            if (!$subscription) continue;

            // Website-abonnementen zijn altijd maandelijks
            $interval = 'month';
            $shouldGenerate = false;
            if (!$subscription->is_active) {
                $shouldGenerate = true;
            } else {
                $nextDate = $subscription->next_invoice_date;
                if ($today->greaterThanOrEqualTo($nextDate)) {
                    $shouldGenerate = true;
                }
            }
            if ($shouldGenerate) {
                $invoice = Invoice::create([
                    'company_id' => $company->id,
                    'subscription_id' => $subscription->id,
                    'invoice_number' => Invoice::generateInvoiceNumber(),
                    'amount' => $subscription->price,
                    'description' => 'Abonnement: ' . $subscription->subscription_plan,
                    'status' => 'pending',
                    'issued_date' => $today,
                    'due_date' => $today->copy()->addDays(14),
                ]);
                $count++;
            }
        }

        // Custom abonnementen: maand of jaar
        $companies = Company::whereNotNull('custom_subscription_id')->get();
        foreach ($companies as $company) {
            $custom = $company->customSubscription;
            if (!$custom) continue;

            $lastInvoice = Invoice::where('company_id', $company->id)
                ->where('custom_subscription_id', $custom->id)
                ->orderByDesc('issued_date')
                ->first();

            $interval = $custom->billing_period === 'jaarlijks' ? 'year' : 'month';
            $shouldGenerate = false;
            if (!$lastInvoice) {
                $shouldGenerate = true;
            } else {
                $nextDate = $lastInvoice->issued_date->copy()->add($interval === 'year' ? '1 year' : '1 month');
                if ($today->greaterThanOrEqualTo($nextDate)) {
                    $shouldGenerate = true;
                }
            }
            if ($shouldGenerate) {
                $invoice = Invoice::create([
                    'company_id' => $company->id,
                    'custom_subscription_id' => $custom->id,
                    'invoice_number' => Invoice::generateInvoiceNumber(),
                    'amount' => $custom->price,
                    'description' => 'Custom abonnement: ' . $custom->billing_period,
                    'status' => 'pending',
                    'issued_date' => $today,
                    'due_date' => $today->copy()->addDays(14),
                ]);
                $count++;
            }
        }

        $this->info("{$count} periodieke facturen gegenereerd.");
    }
}
