<?php

namespace App\Providers;

use App\Models\User;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
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
        Fortify::loginView(fn () => view('auth.Login'));
        Fortify::registerView(fn () => view('auth.register'));
        Fortify::confirmPasswordView(fn () => view('auth.confirm-password'));
        Fortify::twoFactorChallengeView(fn () => view('auth.two-factor-challenge'));

        Fortify::verifyEmailView(function () {
            if (session('pending_custom_subscription_id') && auth()->check()) {
                return redirect()->route('registration.verify-and-secure');
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
            if (!$user) return route('employee.dashboard');
            
            return match($user->role) {
                'super_admin' => route('superadmin.dashboard'),
                'administration_office' => route('administration.dashboard'),
                'employer' => route('employer.dashboard'),
                default => route('employee.dashboard'),
            };
        });

        Fortify::redirects('register', fn () => route('verification.notice'));
        Fortify::redirects('email-verification', fn () => route('verification.success'));
    }
}