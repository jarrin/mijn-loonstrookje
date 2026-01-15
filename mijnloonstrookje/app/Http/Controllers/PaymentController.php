<?php

namespace App\Http\Controllers;

use App\Mail\PaymentConfirmation;
use App\Models\Company;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Mollie\Api\MollieApiClient;

class PaymentController extends Controller
{
    private $mollie;

    public function __construct()
    {
        $this->mollie = new MollieApiClient();
        $this->mollie->setApiKey(config('services.mollie.key'));
    }

    /**
     * Start een nieuwe betaling voor een abonnement
     */
    public function startPayment(Request $request, Subscription $subscription)
    {
        try {
            Log::info('Payment start requested', [
                'is_authenticated' => Auth::check(),
                'user_id' => Auth::id(),
                'subscription_id' => $subscription->id,
                'session_id' => session()->getId(),
            ]);

            // Check of gebruiker is ingelogd
            if (!Auth::check()) {
                // Sla subscription ID op in sessie
                session(['subscription_id' => $subscription->id]);
                
                // Redirect naar registratie met bericht
                return redirect()->route('register')
                    ->with('info', 'Maak eerst een account aan om dit abonnement te kunnen aanschaffen: ' . $subscription->name);
            }

            // Als gebruiker al een actief abonnement heeft, redirect naar dashboard
            $user = Auth::user();
            $company = $user->company;
            
            if ($company && $company->subscription_id) {
                return redirect()->route('employer.dashboard')
                    ->with('info', 'Je hebt al een actief abonnement.');
            }

            // Betaling kan zonder inloggen - metadata bevat alleen subscription info
            $metadata = [
                'subscription_id' => $subscription->id,
                'session_id' => session()->getId(),
            ];

            // Als gebruiker WEL is ingelogd, voeg user/company info toe
            
            if (!$company) {
                // Maak automatisch een company aan
                $company = Company::create([
                    'name' => $user->name . "'s Bedrijf",
                    'email' => $user->email,
                    'subscription_id' => null,
                ]);
                
                $user->company_id = $company->id;
                $user->save();
            }
            
            $metadata['user_id'] = $user->id;
            $metadata['company_id'] = $company->id;

            // Maak een Mollie betaling aan
            $paymentData = [
                'amount' => [
                    'currency' => 'EUR',
                    'value' => number_format($subscription->price, 2, '.', ''),
                ],
                'description' => 'Abonnement: ' . $subscription->name,
                'redirectUrl' => route('payment.return', ['subscription' => $subscription->id]),
                'metadata' => $metadata,
            ];

            // Voeg alleen webhook toe als de app URL publiek toegankelijk is
            $appUrl = config('app.url');
            if (!str_contains($appUrl, 'localhost') && !str_contains($appUrl, '127.0.0.1')) {
                $paymentData['webhookUrl'] = route('payment.webhook');
            }

            $payment = $this->mollie->payments->create($paymentData);

            Log::info('Mollie payment created', [
                'payment_id' => $payment->id,
                'subscription_id' => $subscription->id,
                'metadata' => $metadata,
                'amount' => $subscription->price,
            ]);

            // Redirect gebruiker naar Mollie checkout pagina
            return redirect($payment->getCheckoutUrl());

        } catch (\Exception $e) {
            Log::error('Payment creation failed', [
                'error' => $e->getMessage(),
                'subscription_id' => $subscription->id,
            ]);

            return back()->with('error', 'Er is iets misgegaan bij het aanmaken van de betaling: ' . $e->getMessage());
        }
    }

    /**
     * Gebruiker komt hier terug na betaling op Mollie
     */
    public function returnFromPayment(Request $request, Subscription $subscription)
    {
        // Check betaling status (zonder company_id filter want misschien niet ingelogd)
        if (str_contains(config('app.url'), 'localhost') || str_contains(config('app.url'), '127.0.0.1')) {
            try {
                $sessionId = session()->getId();
                $payments = $this->mollie->payments->page();
                
                foreach ($payments as $payment) {
                    // Match op session_id als niet ingelogd, of op company_id als wel ingelogd
                    $sessionMatch = isset($payment->metadata->session_id) && 
                                   $payment->metadata->session_id == $sessionId;
                    
                    $subscriptionMatch = isset($payment->metadata->subscription_id) && 
                                        $payment->metadata->subscription_id == $subscription->id;
                    
                    if ($sessionMatch && $subscriptionMatch) {
                        Log::info('Payment checked after return', [
                            'subscription_id' => $subscription->id,
                            'payment_id' => $payment->id,
                            'payment_status' => $payment->status,
                            'is_authenticated' => Auth::check(),
                        ]);

                        if ($payment->isPaid()) {
                            // Betaling geslaagd!
                            if (Auth::check()) {
                                $user = Auth::user();
                                
                                // Sla subscription_id op in company
                                if ($user->company) {
                                    $user->company->update([
                                        'subscription_id' => $subscription->id
                                    ]);
                                    
                                    Log::info('Subscription activated for company', [
                                        'company_id' => $user->company->id,
                                        'subscription_id' => $subscription->id,
                                        'payment_id' => $payment->id,
                                    ]);
                                }
                                
                                // Verwijder pending_subscription_id uit sessie
                                session()->forget('pending_subscription_id');
                                
                                // Verstuur bevestigingsmail
                                Mail::to($user->email)->send(new PaymentConfirmation($user, $subscription));
                                
                                Log::info('Payment confirmation email sent', [
                                    'user_email' => $user->email,
                                    'subscription_id' => $subscription->id,
                                ]);
                                
                                // Redirect naar success pagina
                                return view('onboarding.payment-success', [
                                    'subscription' => $subscription
                                ]);
                            } else {
                                // Niet ingelogd - vraag om te registreren/inloggen
                                session(['completed_payment' => [
                                    'payment_id' => $payment->id,
                                    'subscription_id' => $subscription->id,
                                ]]);
                                
                                return redirect()->route('login')
                                    ->with('success', 'Betaling succesvol! Log in of registreer om je abonnement te activeren.');
                            }
                        } elseif ($payment->isFailed() || $payment->isCanceled()) {
                            return redirect()->route('website')
                                ->with('error', 'Betaling is niet voltooid.');
                        }
                        
                        break;
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error checking payment status', [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Default redirect
        $redirectRoute = Auth::check() ? 'employer.dashboard' : 'website';
        return redirect()->route($redirectRoute)
            ->with('info', 'Terug van Mollie. Check de logs voor betaling status.');
    }

    /**
     * Mollie webhook - wordt aangeroepen wanneer betaling status verandert
     */
    public function webhook(Request $request)
    {
        try {
            $paymentId = $request->input('id');
            
            if (!$paymentId) {
                Log::warning('Webhook called without payment ID');
                return response('No payment ID', 400);
            }

            $payment = $this->mollie->payments->get($paymentId);
            
            Log::info('Webhook received', [
                'payment_id' => $paymentId,
                'status' => $payment->status,
                'metadata' => $payment->metadata,
            ]);

            // Als betaling is geslaagd - LOG maar activeer NIET (test mode)
            if ($payment->isPaid()) {
                $metadata = $payment->metadata;
                $subscriptionId = $metadata->subscription_id ?? null;
                $companyId = $metadata->company_id ?? null;

                Log::info('Payment successful - test mode, NOT activating subscription', [
                    'company_id' => $companyId,
                    'subscription_id' => $subscriptionId,
                    'payment_id' => $paymentId,
                ]);

                // TODO: Uncommment onderstaande code om daadwerkelijk het abonnement te activeren
                /*
                if ($subscriptionId && $companyId) {
                    $company = Company::find($companyId);
                    
                    if ($company) {
                        $company->subscription_id = $subscriptionId;
                        $company->save();

                        Log::info('Company subscription updated', [
                            'company_id' => $companyId,
                            'subscription_id' => $subscriptionId,
                        ]);
                    }
                }
                */
            }

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('Webhook processing failed', [
                'error' => $e->getMessage(),
                'payment_id' => $request->input('id'),
            ]);

            return response('Error', 500);
        }
    }
}
