<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\PaymentController;
use App\Models\Subscription;

// Website routes 
Route::get('/website', function () {
    $subscriptions = Subscription::all();
    return view('website.website', compact('subscriptions'));
})->name('website');

// Home page route dashboard
Route::get('/', function () {
    // Redirect authenticated users to their dashboard
    if (auth()->check()) {
        $user = auth()->user();
        return match($user->role) {
            'employee' => redirect()->route('employee.documents'),
            'employer' => redirect()->route('employer.dashboard'),
            'administration_office' => redirect()->route('administration.dashboard'),
            'super_admin' => redirect()->route('superadmin.dashboard'),
            default => view('auth.login'),
        };
    }
    
    return view('auth.login');
})->name('auth');

Route::get('/home', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return match($user->role) {
            'employee' => redirect()->route('employee.documents'),
            'employer' => redirect()->route('employer.dashboard'),
            'administration_office' => redirect()->route('administration.dashboard'),
            'super_admin' => redirect()->route('superadmin.dashboard'),
            default => redirect('/'),
        };
    }
    
    return redirect('/');
})->name('home');

// Protected dashboard routes
Route::middleware(['auth', 'verified', 'paid.subscription'])->group(function () {
    // Employee routes
    Route::middleware('role:employee')->group(function () {
        Route::get('/employee/documents', [EmployeeController::class, 'documents'])->name('employee.documents');
    });
    
    // Employer routes
    Route::middleware('role:employer')->group(function () {
        Route::get('/employer/dashboard', [EmployerController::class, 'dashboard'])->name('employer.dashboard');
        Route::get('/employer/employees', [EmployerController::class, 'employees'])->name('employer.employees');
        Route::delete('/employer/employees/{employee}', [EmployerController::class, 'destroyEmployee'])->name('employer.employee.destroy');
        Route::get('/employer/documents', [EmployerController::class, 'documents'])->name('employer.documents');
        
        // Administration office management routes
        Route::get('/employer/admin-offices', [EmployerController::class, 'adminOffices'])->name('employer.admin-offices');
        Route::post('/employer/admin-offices/invite', [EmployerController::class, 'inviteAdminOffice'])->name('employer.admin-offices.invite');
        Route::put('/employer/admin-offices/{adminOffice}', [EmployerController::class, 'updateAdminOffice'])->name('employer.admin-offices.update');
        Route::delete('/employer/admin-offices/{adminOffice}', [EmployerController::class, 'destroyAdminOffice'])->name('employer.admin-offices.destroy');
        
        // Invitation routes
        Route::post('/employer/invite-employee', [App\Http\Controllers\InvitationController::class, 'sendInvitation'])->name('employer.send.invitation');
        Route::delete('/invitations/{id}', [App\Http\Controllers\InvitationController::class, 'deleteInvitation'])->name('invitation.delete');
    });
    
    // Shared route for employer and administration_office - employee documents
    Route::middleware('role:employer,administration_office')->group(function () {
        Route::get('/employer/employees/{employee}/documents', [EmployerController::class, 'employeeDocuments'])->name('employer.employee.documents');
    });
    
    // Administration routes
    Route::middleware('role:administration_office')->group(function () {
        Route::get('/administration/dashboard', [AdministrationController::class, 'dashboard'])->name('administration.dashboard');
        Route::get('/administration/company/{company}/employees', [AdministrationController::class, 'companyEmployees'])->name('administration.company.employees');
    });
    
    // Document routes - accessible by employer, administration_office, and employee (for view/download)
    Route::middleware('role:employer,administration_office,employee')->group(function () {
        Route::get('/documents/{id}/view', [\App\Http\Controllers\DocumentController::class, 'view'])->name('documents.view');
        Route::get('/documents/{id}/download', [\App\Http\Controllers\DocumentController::class, 'download'])->name('documents.download');
        Route::post('/documents/bulk-download', [\App\Http\Controllers\DocumentController::class, 'bulkDownload'])->name('documents.bulk-download');
    });
    
    // Document management routes - only for employer and administration_office
    Route::middleware('role:employer,administration_office')->group(function () {
        Route::get('/documents/upload/{employee?}', [\App\Http\Controllers\DocumentController::class, 'create'])->name('documents.upload');
        Route::post('/documents', [\App\Http\Controllers\DocumentController::class, 'store'])->name('documents.store');
        Route::get('/documents/{id}/edit', [\App\Http\Controllers\DocumentController::class, 'edit'])->name('documents.edit');
        Route::put('/documents/{id}', [\App\Http\Controllers\DocumentController::class, 'update'])->name('documents.update');
        Route::delete('/documents/{id}', [\App\Http\Controllers\DocumentController::class, 'destroy'])->name('documents.destroy');
        Route::get('/documents/deleted', [\App\Http\Controllers\DocumentController::class, 'deleted'])->name('documents.deleted');
        Route::post('/documents/{id}/restore', [\App\Http\Controllers\DocumentController::class, 'restore'])->name('documents.restore');
    });
    
    // Super Admin routes
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/superadmin/dashboard', [SuperAdminController::class, 'dashboard'])->name('superadmin.dashboard');
        Route::get('/superadmin/users/{user}/edit', [SuperAdminController::class, 'editUser'])->name('superadmin.users.edit');
        Route::put('/superadmin/users/{user}', [SuperAdminController::class, 'updateUser'])->name('superadmin.users.update');
        Route::delete('/superadmin/users/{user}', [SuperAdminController::class, 'destroyUser'])->name('superadmin.users.destroy');
        
        Route::get('/superadmin/subscriptions', [SuperAdminController::class, 'subscriptions'])->name('superadmin.subscriptions');
        Route::put('/superadmin/subscriptions/{subscription}', [SuperAdminController::class, 'updateSubscription'])->name('superadmin.subscriptions.update');
        
        // Custom subscriptions routes
        Route::post('/superadmin/custom-subscriptions', [SuperAdminController::class, 'storeCustomSubscription'])->name('superadmin.custom-subscriptions.store');
        Route::put('/superadmin/custom-subscriptions/{customSubscription}', [SuperAdminController::class, 'updateCustomSubscription'])->name('superadmin.custom-subscriptions.update');
        Route::delete('/superadmin/custom-subscriptions/{customSubscription}', [SuperAdminController::class, 'destroyCustomSubscription'])->name('superadmin.custom-subscriptions.destroy');
        Route::post('/superadmin/custom-subscriptions/{customSubscription}/invite', [SuperAdminController::class, 'inviteCustomSubscription'])->name('superadmin.custom-subscriptions.invite');
        Route::delete('/superadmin/custom-subscriptions/{customSubscription}/invitations/{invitation}', [SuperAdminController::class, 'cancelCustomSubscriptionInvitation'])->name('superadmin.custom-subscriptions.cancel-invitation');
        Route::delete('/superadmin/custom-subscriptions/{customSubscription}/companies/{company}', [SuperAdminController::class, 'removeCompanyFromCustomSubscription'])->name('superadmin.custom-subscriptions.remove-company');
        
        // Invitation cancel route (used in view)
        Route::delete('/superadmin/invitations/{invitation}', [SuperAdminController::class, 'cancelInvitation'])->name('superadmin.invitations.cancel');
        
        Route::get('/superadmin/logs', [SuperAdminController::class, 'logs'])->name('superadmin.logs');
        Route::get('/superadmin/facturation', [SuperAdminController::class, 'facturation'])->name('superadmin.facturation');
    });
});

// Email verification routes
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', function () {
        $user = auth()->user();
        
        // Custom subscription flow
        if (session('pending_custom_subscription_id')) {
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
    })->name('verification.notice');
    
    Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\CustomVerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    
    Route::get('/email/verified-success', function () {
        return view('auth.email-verified-simple');
    })->name('verification.success');
});

// Employer verify-and-secure route (Step 2)
Route::middleware(['auth'])->group(function () {
    Route::get('/employer/verify-and-secure', function () {
        $user = auth()->user();
        
        // Check if user is employer
        if (!$user || $user->role !== 'employer') {
            return redirect()->route('auth');
        }
        
        // If already verified and has 2FA, redirect appropriately
        if ($user->hasVerifiedEmail() && $user->two_factor_confirmed_at) {
            if (session('pending_subscription_id')) {
                return redirect()->route('payment.checkout', ['subscription' => session('pending_subscription_id')]);
            }
            return redirect()->route('employer.dashboard');
        }
        
        return view('registration.employer.verify-and-secure');
    })->name('employer.verify-and-secure');
});

// Employee verify-and-secure route (Step 2)
Route::middleware(['auth'])->group(function () {
    Route::get('/employee/verify-and-secure', function () {
        $user = auth()->user();
        
        // Check if user is employee
        if (!$user || $user->role !== 'employee') {
            return redirect()->route('auth');
        }
        
        // If already verified and has 2FA, redirect to dashboard
        if ($user->hasVerifiedEmail() && $user->two_factor_confirmed_at) {
            return redirect()->route('employee.dashboard');
        }
        
        return view('registration.employee.verify-and-secure');
    })->name('employee.verify-and-secure');
});

// Custom subscription flow routes (Step 2 & 3) - met flow guard
Route::middleware(['auth', 'custom.flow'])->group(function () {
    // Step 2: Verify email + 2FA
    Route::get('/registration/verify-and-secure', function () {
        if (!session('pending_custom_subscription_id')) {
            return redirect()->route('employer.dashboard');
        }
        return view('registration.custom.verify-and-secure');
    })->name('registration.verify-and-secure');
    
    // Step 3: Payment checkout
    Route::get('/payment/custom-checkout/{customSubscription}', function (\App\Models\CustomSubscription $customSubscription) {
        if (session('pending_custom_subscription_id') != $customSubscription->id) {
            return redirect()->route('employer.dashboard');
        }
        return view('registration.custom.checkout', compact('customSubscription'));
    })->middleware('verified')->name('payment.custom-checkout');
});

// Onboarding routes (auth + verified but WITHOUT paid.subscription middleware to avoid redirect loop)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/onboarding/setup-2fa', function () {
        return view('onboarding.setup-2fa');
    })->name('onboarding.setup-2fa');
    
    // Profile settings page
    Route::get('/profile/settings', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.settings');
    Route::post('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::post('/profile/branding', [App\Http\Controllers\ProfileController::class, 'updateBranding'])->name('profile.branding.update');
    Route::post('/profile/two-factor-recovery-codes', [App\Http\Controllers\TwoFactorRecoveryCodeController::class, 'store'])->name('profile.two-factor-recovery-codes');
    
    // 2FA settings page for all authenticated users
    Route::get('/profile/two-factor-authentication', function () {
        return view('profile.two-factor-authentication');
    })->name('profile.two-factor-authentication');
    
    // Regular subscription checkout
    Route::get('/onboarding/checkout/{subscription}', function (\App\Models\Subscription $subscription) {
        // If user already has an active subscription, redirect to dashboard
        if (auth()->user()->company && auth()->user()->company->subscription_id) {
            return redirect()->route('employer.dashboard');
        }
        
        return view('registration.employer.checkout', compact('subscription'));
    })->name('payment.checkout');
});

// Password confirmation routes
Route::middleware(['auth'])->group(function () {
    Route::get('/user/confirm-password', function () {
        return view('auth.confirm-password');
    })->name('password.confirm');
    
    Route::post('/user/confirm-password', function (Illuminate\Http\Request $request) {
        if (! Hash::check($request->password, $request->user()->password)) {
            return back()->withErrors(['password' => 'Het opgegeven wachtwoord is onjuist.']);
        }
        
        $request->session()->put('auth.password_confirmed_at', time());
        
        return redirect()->intended();
    });
});

// Public invitation routes (not requiring authentication)
Route::get('/invitation/accept/{token}', [InvitationController::class, 'acceptInvitation'])->name('invitation.accept');

// Payment routes
Route::post('/payment/start/{subscription}', [PaymentController::class, 'startPayment'])->name('payment.start');
Route::get('/payment/return/{subscription}', [PaymentController::class, 'returnFromPayment'])->name('payment.return');
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');

// Custom subscription payment routes
Route::post('/payment/custom/start/{customSubscription}', [PaymentController::class, 'startCustomPayment'])->name('payment.start.custom');
Route::get('/payment/custom/return/{customSubscription}', [PaymentController::class, 'returnFromCustomPayment'])->name('payment.return.custom');

// Invitation routes
Route::post('/invitation/accept/{token}', [InvitationController::class, 'loginAndAcceptInvitation'])->name('invitation.login.accept');
Route::post('/invitation/register/{token}', [InvitationController::class, 'registerInvitedEmployee'])->name('invitation.register');