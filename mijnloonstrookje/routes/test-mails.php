<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmployeeInvitation;
use App\Mail\AdminOfficeInvitationNew;
use App\Mail\AdminOfficeInvitationExisting;
use App\Mail\CustomSubscriptionInvitation;
use App\Mail\InvoiceCreated;
use App\Mail\PaymentConfirmation;
use App\Models\User;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Subscription;
use App\Models\CustomSubscription;

Route::get('/test-all-mails', function () {
    $to = request('to', 'test@example.com');
    $user = User::first() ?? User::factory()->make();
    $company = Company::first() ?? Company::factory()->make();
    $invoice = Invoice::first() ?? Invoice::factory()->make();
    $subscription = Subscription::first() ?? Subscription::factory()->make();
    $customSubscription = CustomSubscription::first() ?? CustomSubscription::factory()->make();

    // Employee Invitation
    Mail::to($to)->send(new EmployeeInvitation(
        $company->name,
        $user->name,
        'https://mijnloonstrookje.nl/activate/employee/test'
    ));

    // Admin Office Invitation (New)
    Mail::to($to)->send(new AdminOfficeInvitationNew(
        $company->name,
        $user->name,
        'https://mijnloonstrookje.nl/activate/adminoffice/new/test'
    ));

    // Admin Office Invitation (Existing)
    Mail::to($to)->send(new AdminOfficeInvitationExisting(
        $company->name,
        $user->name,
        'https://mijnloonstrookje.nl/activate/adminoffice/existing/test'
    ));

    // Custom Subscription Invitation
    Mail::to($to)->send(new CustomSubscriptionInvitation(
        $customSubscription,
        'https://mijnloonstrookje.nl/activate/custom/test'
    ));

    // Invoice Created
    Mail::to($to)->send(new InvoiceCreated($invoice));

    // Payment Confirmation
    Mail::to($to)->send(new PaymentConfirmation($user, $company, $subscription));

    return 'Alle testmails zijn verstuurd naar ' . $to;
});
