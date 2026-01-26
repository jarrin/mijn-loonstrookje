<?php

namespace App\Providers;

use App\Models\User;
use App\Actions\Fortify\CreateNewUser;
use App\Services\AuditLogService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Contracts\TwoFactorConfirmedResponse;
use App\Http\Responses\TwoFactorConfirmedResponse as CustomTwoFactorConfirmedResponse;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CreatesNewUsers::class, CreateNewUser::class);
        $this->app->singleton(TwoFactorConfirmedResponse::class, CustomTwoFactorConfirmedResponse::class);
    }

    public function boot(): void
    {
        // Rate limiters
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->email . $request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // Views
        Fortify::loginView(fn () => view('auth.login'));
        Fortify::registerView(fn () => view('auth.register'));
        Fortify::confirmPasswordView(fn () => view('auth.confirm-password'));
        Fortify::twoFactorChallengeView(fn () => view('auth.two-factor-challenge'));

        Fortify::verifyEmailView(function () {
            $user = auth()->user();
            
            // Custom subscription flow
            if (session('pending_custom_subscription_id') && $user) {
                return redirect()->route('registration.verify-and-secure');
            }
            
            // Employer flow
            if ($user && $user->role === 'employer') {
                return redirect()->route('employer.verify-and-secure');
            }
            
            // Employee flow
            if ($user && $user->role === 'employee') {
                return redirect()->route('employee.verify-and-secure');
            }
            
            return view('auth.verify-email');
        });

        // Custom authentication
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();
            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }
        });

        // Redirects
        Fortify::redirects('login', function () {
            $user = auth()->user();
            if (!$user) return route('employee.documents');
            
            if (!$user) {
                return route('employee.documents');
            }
            
            // Route based on user role
            return match($user->role) {
                'super_admin' => route('superadmin.dashboard'),
                'administration_office' => route('administration.dashboard'),
                'employer' => route('employer.dashboard'),
                'employee' => route('employee.documents'),
                default => route('employee.documents'),
            };
        });

        // Custom redirect after registration
        Fortify::redirects('register', function () {
            $user = auth()->user();
            
            Log::info('User registered', [
                'user_id' => $user->id,
                'email_verified' => $user->hasVerifiedEmail(),
                'has_pending_subscription' => session()->has('pending_subscription_id'),
            ]);
            
            // Na registratie moet gebruiker eerst email verifiëren
            // Verificatie email wordt automatisch verstuurd door Laravel
            return route('verification.notice');
        });

        // Custom redirect after email verification
        Fortify::redirects('email-verification', function () {
            $user = auth()->user();
            
            Log::info('Email verified - redirect to 2FA setup', [
                'user_id' => $user->id,
            ]);
            
            // Na email verificatie → 2FA setup onboarding pagina
            return route('onboarding.setup-2fa');
        });
        
        // Listen for successful login events and log them
        Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
            $user = $event->user;
            
            // For admin offices, log once without company_id (they work across multiple companies)
            // Employers will still see these logins via the User relationship check
            if ($user->role === 'administration_office') {
                AuditLogService::logLogin($user->id, null);
            } else {
                // For other roles, log with their company_id
                AuditLogService::logLogin($user->id, $user->company_id);
            }
        });
    }
}