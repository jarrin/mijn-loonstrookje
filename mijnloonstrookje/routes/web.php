<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;

// Home page route
Route::get('/', function () {
    return view('HomePage');
})->name('home');

// Protected dashboard routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/employee/dashboard', [DashboardController::class, 'employee'])->name('employee.dashboard');
    Route::get('/employer/dashboard', [DashboardController::class, 'employer'])->name('employer.dashboard');
    Route::get('/administration/dashboard', [DashboardController::class, 'administration'])->name('administration.dashboard');
    Route::get('/superadmin/dashboard', [DashboardController::class, 'superAdmin'])->name('superadmin.dashboard');
    
    // Two-factor authentication management
    Route::get('/profile/two-factor-authentication', function () {
        return view('profile.two-factor-authentication');
    })->name('profile.two-factor-authentication');
});