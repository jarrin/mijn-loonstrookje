<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Invoice;
use App\Models\Invitation;
use App\Models\User;
use App\Models\CustomSubscription;
use App\Models\Subscription;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceCreated;
use App\Mail\AdminOfficeInvitation;
use App\Mail\EmployeeInvitation;
use App\Mail\CustomSubscriptionInvitation;
use App\Mail\PaymentConfirmation;
use Illuminate\Auth\Notifications\VerifyEmail;

class SendAllInvoicesMail extends Command
{
    protected $signature = 'mail:send-all-invoices';
    protected $description = 'Stuur alle mailtypes in één keer naar een testmail voor design-check';

    public function handle()
    {
        $testMail = 'design-check@example.com';
        $count = 0;

        // Test user voor notificaties
        $testUser = \App\Models\User::first() ?? new \App\Models\User([
            'name' => 'Test Gebruiker',
            'email' => $testMail,
            'role' => 'employee',
        ]);

        // Stuur Laravel standaard verificatie-mail (notification)
        try {
            $testUser->notify(new VerifyEmail);
            $count++;
        } catch (\Throwable $e) {
            $this->warn('Kon verify notification niet sturen: ' . $e->getMessage());
        }

        // Alle uitnodigingen
        foreach (Invitation::all() as $invitation) {
            // AdminOfficeInvitation
            if ($invitation->role === 'administration_office') {
                Mail::to($testMail)->queue(new AdminOfficeInvitation($invitation, 'Test Werkgever', 'Test Bedrijf', true));
                $count++;
            }
            // EmployeeInvitation
            if ($invitation->role === 'employee') {
                Mail::to($testMail)->queue(new EmployeeInvitation($invitation, 'Test Werkgever', 'Test Bedrijf'));
                $count++;
            }
            // CustomSubscriptionInvitation
            if ($invitation->role === 'employer' && $invitation->custom_subscription_id) {
                $custom = CustomSubscription::find($invitation->custom_subscription_id);
                if ($custom) {
                    Mail::to($testMail)->queue(new CustomSubscriptionInvitation($invitation, $custom));
                    $count++;
                }
            }
        }

        // Alle facturen
        foreach (Invoice::all() as $invoice) {
            Mail::to($testMail)->queue(new InvoiceCreated($invoice));
            $count++;
        }

        // Alle subscriptions (voor payment confirmation)
        $user = User::has('company')->first();
        $subscription = Subscription::first();
        if ($user && $subscription) {
            Mail::to($testMail)->queue(new PaymentConfirmation($user, $subscription));
            $count++;
        } else {
            // Fallback: test user + test company
            $company = \App\Models\Company::first() ?? new \App\Models\Company(['name' => 'Testbedrijf', 'kvk_number' => '12345678']);
            $user = new \App\Models\User(['name' => 'Test Gebruiker', 'email' => $testMail]);
            $user->setRelation('company', $company);
            if ($subscription) {
                Mail::to($testMail)->queue(new PaymentConfirmation($user, $subscription));
                $count++;
            }
        }

        $this->info("{$count} testmails verstuurd naar $testMail.");
        return 0;
    }
}
