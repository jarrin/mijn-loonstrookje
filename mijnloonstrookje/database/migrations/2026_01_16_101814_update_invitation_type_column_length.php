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
            // Change invitation_type from enum to varchar to support custom_subscription_invite
            $table->string('invitation_type', 50)->default('new_account')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            // Revert back to enum (though this might lose data if custom_subscription_invite exists)
            $table->enum('invitation_type', ['new_account', 'company_access'])->default('new_account')->change();
        });
    }
};
