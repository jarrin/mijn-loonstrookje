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
            default => view('auth.Login'),
        };
    }
    
    return view('auth.Login');
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
        Route::get('/administration/employees', [AdministrationController::class, 'employees'])->name('administration.employees');
        Route::get('/administration/documents', [AdministrationController::class, 'documents'])->name('administration.documents');
        Route::get('/administration/company/{company}', [AdministrationController::class, 'showCompany'])->name('administration.company.show');
        Route::get('/administration/company/{company}/employees', [AdministrationController::class, 'companyEmployees'])->name('administration.company.employees');
        Route::get('/administration/company/{company}/documents', [AdministrationController::class, 'companyDocuments'])->name('administration.company.documents');
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
        
        Route::get('/superadmin/logs', [\App\Http\Controllers\AuditLogController::class, 'superAdminLogs'])->name('superadmin.logs');
        Route::get('/superadmin/facturation', [SuperAdminController::class, 'facturation'])->name('superadmin.facturation');
    });
});

// Onboarding routes (auth + verified but WITHOUT paid.subscription middleware to avoid redirect loop)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/onboarding/setup-2fa', function () {
        return view('onboarding.setup-2fa');
    })->name('onboarding.setup-2fa');
    
    // Profile settings page for all authenticated users
    Route::get('/profile/settings', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.settings');
    Route::post('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::post('/profile/branding', [\App\Http\Controllers\ProfileController::class, 'updateBranding'])->name('profile.branding.update');
    
    Route::get('/onboarding/checkout/{subscription}', function (\App\Models\Subscription $subscription) {
        // If user already has an active subscription, redirect to dashboard
        if (auth()->user()->company && auth()->user()->company->subscription_id) {
            return redirect()->route('employer.dashboard');
        }
        
        return view('onboarding.checkout', compact('subscription'));
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
Route::post('/invitation/accept/{token}', [InvitationController::class, 'loginAndAcceptInvitation'])->name('invitation.login.accept');
Route::post('/invitation/register/{token}', [InvitationController::class, 'registerInvitedEmployee'])->name('invitation.register');