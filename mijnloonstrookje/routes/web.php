<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\InvitationController;
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
            'employee' => redirect()->route('employee.dashboard'),
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
            'employee' => redirect()->route('employee.dashboard'),
            'employer' => redirect()->route('employer.dashboard'),
            'administration_office' => redirect()->route('administration.dashboard'),
            'super_admin' => redirect()->route('superadmin.dashboard'),
            default => redirect('/'),
        };
    }
    
    return redirect('/');
})->name('home');

// Protected dashboard routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Employee routes
    Route::middleware('role:employee')->group(function () {
        Route::get('/employee/dashboard', [EmployeeController::class, 'dashboard'])->name('employee.dashboard');
    });
    
    // Employer routes
    Route::middleware('role:employer')->group(function () {
        Route::get('/employer/dashboard', [EmployerController::class, 'dashboard'])->name('employer.dashboard');
        Route::get('/employer/employees', [EmployerController::class, 'employees'])->name('employer.employees');
        Route::get('/employer/employees/{employee}/documents', [EmployerController::class, 'employeeDocuments'])->name('employer.employee.documents');
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
    
    // Administration routes
    Route::middleware('role:administration_office')->group(function () {
        Route::get('/administration/dashboard', [AdministrationController::class, 'dashboard'])->name('administration.dashboard');
        Route::get('/administration/employees', [AdministrationController::class, 'employees'])->name('administration.employees');
        Route::get('/administration/documents', [AdministrationController::class, 'documents'])->name('administration.documents');
    });
    
    // Super Admin routes
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/superadmin/dashboard', [SuperAdminController::class, 'dashboard'])->name('superadmin.dashboard');
        Route::get('/superadmin/users/{user}/edit', [SuperAdminController::class, 'editUser'])->name('superadmin.users.edit');
        Route::put('/superadmin/users/{user}', [SuperAdminController::class, 'updateUser'])->name('superadmin.users.update');
        Route::delete('/superadmin/users/{user}', [SuperAdminController::class, 'destroyUser'])->name('superadmin.users.destroy');
        
        Route::get('/superadmin/subscriptions', [SuperAdminController::class, 'subscriptions'])->name('superadmin.subscriptions');
        Route::put('/superadmin/subscriptions/{subscription}', [SuperAdminController::class, 'updateSubscription'])->name('superadmin.subscriptions.update');
        
        Route::get('/superadmin/logs', [SuperAdminController::class, 'logs'])->name('superadmin.logs');
        Route::get('/superadmin/facturation', [SuperAdminController::class, 'facturation'])->name('superadmin.facturation');
    });
    
    // Two-factor authentication management (accessible to all authenticated users)
    Route::get('/profile/two-factor-authentication', function () {
        return view('profile.two-factor-authentication');
    })->name('profile.two-factor-authentication');
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
Route::post('/invitation/accept/{token}', [InvitationController::class, 'loginAndAcceptInvitation'])->name('invitation.login.accept');
Route::post('/invitation/register/{token}', [InvitationController::class, 'registerInvitedEmployee'])->name('invitation.register');