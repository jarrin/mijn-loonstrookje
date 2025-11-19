<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Models\Subscription;

// Website routes 
Route::get('/website', function () {
    $subscriptions = Subscription::all();
    return view('website.website', compact('subscriptions'));
})->name('website');

// Home page route dashboard
Route::get('/', function () {
    return view('HomePage');
})->name('home');

// Protected dashboard routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Employee routes
    Route::get('/employee/dashboard', [DashboardController::class, 'employee'])->name('employee.dashboard');
    
    // Employer routes
    Route::get('/employer/dashboard', [DashboardController::class, 'employer'])->name('employer.dashboard');
    Route::get('/employer/employees', function () {
        return view('employer.EmployerEmployeeList');
    })->name('employer.employees');
    Route::get('/employer/documents', function () {
        return view('employer.EmployerEmployeeDocuments');
    })->name('employer.documents');
    Route::get('/employer/admin-offices', function () {
        return view('employer.EmployerAdminOfficeList');
    })->name('employer.admin-offices');
    
    // Administration routes
    Route::get('/administration/dashboard', [DashboardController::class, 'administration'])->name('administration.dashboard');
    Route::get('/administration/employees', function () {
        return view('admin.AdminOfficeEmployeeList');
    })->name('administration.employees');
    Route::get('/administration/documents', function () {
        return view('admin.AdminOfficeDocuments');
    })->name('administration.documents');
    
    // Super Admin routes
    Route::get('/superadmin/dashboard', [DashboardController::class, 'superAdmin'])->name('superadmin.dashboard');
    Route::get('/superadmin/subscriptions', function () {
        return view('superadmin.SuperAdminSubs');
    })->name('superadmin.subscriptions');
    Route::get('/superadmin/logs', function () {
        return view('superadmin.SuperAdminLogs');
    })->name('superadmin.logs');
    Route::get('/superadmin/facturation', function () {
        return view('superadmin.SuperAdminFacturation');
    })->name('superadmin.facturation');
    // Super admin user management
    Route::delete('/superadmin/users/{user}', [DashboardController::class, 'destroyUser'])->name('superadmin.users.destroy');
    
    // Two-factor authentication management
    Route::get('/profile/two-factor-authentication', function () {
        return view('profile.two-factor-authentication');
    })->name('profile.two-factor-authentication');
});