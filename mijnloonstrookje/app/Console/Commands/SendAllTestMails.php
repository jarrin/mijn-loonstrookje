<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmployeeInvitation;
use App\Mail\AdminOfficeInvitation;
use App\Mail\CustomSubscriptionInvitation;
use App\Mail\InvoiceCreated;
use App\Mail\PaymentConfirmation;
use App\Mail\VerifyEmail;
use App\Models\User;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Subscription;
use App\Models\CustomSubscription;
use App\Models\Invitation;
use App\Console\Commands\DummyInvitationFactory;

class SendAllTestMails extends Command
{
    protected $signature = 'mail:send-all-test {--to= : E-mail adres om naar te sturen}';
    protected $description = 'Stuur alle mail-templates naar een testadres';

    public function handle()
    {
        $to = $this->option('to') ?: config('mail.from.address');
        $user = User::first() ?? User::factory()->make();
        $company = Company::first() ?? Company::factory()->make();

        $invoice = Invoice::first();
        if (!$invoice) {
            $invoice = new Invoice([
                'invoice_number' => 'INV-2026-0001',
                'amount' => 123.45,
                'description' => 'Testfactuur voor design',
                'status' => 'open',
                'issued_date' => now(),
                'due_date' => now()->addDays(14),
            ]);
            $invoice->setRelation('company', $company);
        }

        $subscription = Subscription::first();
        if (!$subscription) {
            $subscription = new Subscription([
                'name' => 'Test Abonnement',
                'price' => 49.95,
                'feature_1' => 'Feature 1',
                'feature_2' => 'Feature 2',
                'feature_3' => 'Feature 3',
            ]);
        }

        $customSubscription = CustomSubscription::first();
        if (!$customSubscription) {
            $customSubscription = new CustomSubscription([
                'price' => 99.99,
                'billing_period' => 'maandelijks',
                'max_users' => 10,
            ]);
        }


        $employeeInvitation = DummyInvitationFactory::make($user, $company, 'employee', 'employee');
        Mail::to($to)->send(new EmployeeInvitation(
            $employeeInvitation,
            $user->name,
            $company->name
        ));
        $this->info('EmployeeInvitation verstuurd');

        $adminOfficeInvitationNew = DummyInvitationFactory::make($user, $company, 'admin_office', 'admin_office_new');
        Mail::to($to)->send(new AdminOfficeInvitation(
            $adminOfficeInvitationNew,
            $user->name,
            $company->name,
            true // isNewAccount
        ));
        $this->info('AdminOfficeInvitation (nieuw account) verstuurd');

        $adminOfficeInvitationExisting = DummyInvitationFactory::make($user, $company, 'admin_office', 'admin_office_existing');
        Mail::to($to)->send(new AdminOfficeInvitation(
            $adminOfficeInvitationExisting,
            $user->name,
            $company->name,
            false // bestaand account
        ));
        $this->info('AdminOfficeInvitation (bestaand account) verstuurd');


        $customSubscriptionInvitation = DummyInvitationFactory::make($user, $company, 'employer', 'custom_subscription');
        Mail::to($to)->send(new CustomSubscriptionInvitation(
            $customSubscriptionInvitation,
            $customSubscription
        ));
        $this->info('CustomSubscriptionInvitation verstuurd');

        Mail::to($to)->send(new InvoiceCreated($invoice));
        $this->info('InvoiceCreated verstuurd');

        // Zorg dat de user altijd een company heeft (ook als dummy)
        if (!$user->company) {
            $user->setRelation('company', $company);
        }
        Mail::to($to)->send(new PaymentConfirmation($user, $subscription));
        $this->info('PaymentConfirmation verstuurd');

        // VerifyEmail test mail
        $verificationUrl = 'https://mijnloonstrookje.nl/verify/testtoken';
        $userName = $user->name ?? 'Test Gebruiker';
        Mail::to($to)->send(new VerifyEmail($verificationUrl, $userName));
        $this->info('VerifyEmail verstuurd');

        $this->info('Alle testmails zijn verstuurd naar ' . $to);
    }
}
