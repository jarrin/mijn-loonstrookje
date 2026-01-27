<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceCreated;

class CreateOpenInvoice extends Command
{
    protected $signature = 'invoice:create-open {company_name} {amount} {--description=}';
    protected $description = 'Maak een openstaande factuur aan voor een bedrijf (op naam)';

    public function handle()
    {

        $companyName = $this->argument('company_name');
        $amount = $this->argument('amount');
        $description = $this->option('description') ?? 'Handmatig aangemaakte open factuur';

        $companies = Company::where('name', 'LIKE', $companyName)->get();
        if ($companies->count() === 0) {
            $this->error('Bedrijf niet gevonden met naam: ' . $companyName);
            return 1;
        }
        if ($companies->count() > 1) {
            $this->error('Meerdere bedrijven gevonden met deze naam. Maak de naam specifieker.');
            foreach ($companies as $c) {
                $this->line('ID: ' . $c->id . ' | Naam: ' . $c->name);
            }
            return 1;
        }
        $company = $companies->first();

        $invoice = Invoice::create([
            'company_id' => $company->id,
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'amount' => $amount,
            'description' => $description,
            'status' => 'pending',
            'issued_date' => now(),
            'due_date' => now()->addDays(14),
        ]);

        // Mail wordt automatisch verstuurd via Invoice::boot()

        $this->info('Openstaande factuur aangemaakt: #' . $invoice->invoice_number . ' voor bedrijf ' . $company->name . ' (€' . $amount . ')');
        return 0;
    }
}
