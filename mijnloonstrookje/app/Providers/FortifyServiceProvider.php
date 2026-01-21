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
use Illuminate\Support\Facades\Log;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CreatesNewUsers::class, CreateNewUser::class);
    }

    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;
            return Limit::perMinute(5)->by($email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // Views
        Fortify::loginView(function () {
            return view('auth.Login');
        });

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });

        Fortify::confirmPasswordView(function () {
            return view('auth.confirm-password');
        });

        Fortify::twoFactorChallengeView(function () {
            return view('auth.two-factor-challenge');
        });

        // Custom authentication logic
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }
        });

        // Custom redirect after login based on role
        Fortify::redirects('login', function () {
            $user = auth()->user();
            
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