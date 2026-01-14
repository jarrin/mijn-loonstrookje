<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            // Drop the existing unique constraint on email
            $table->dropUnique('invitations_email_unique');
            
            // Add composite unique constraint on email and company_id
            // This allows same email to have multiple invitations for different companies
            $table->unique(['email', 'company_id', 'status'], 'invitations_email_company_status_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            // Drop the composite unique constraint
            $table->dropUnique('invitations_email_company_status_unique');
            
            // Restore the original unique constraint on email
            $table->unique('email');
        });
    }
};
