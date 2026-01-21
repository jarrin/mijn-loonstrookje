<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\AuditLog;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule audit log cleanup - runs daily at 2:00 AM
Schedule::call(function () {
    $deletedCount = AuditLog::where('created_at', '<', now()->subDays(30))->delete();
    \Log::info("Audit logs cleanup: deleted {$deletedCount} logs older than 30 days");
})->daily()->at('02:00')->name('cleanup-old-audit-logs');
