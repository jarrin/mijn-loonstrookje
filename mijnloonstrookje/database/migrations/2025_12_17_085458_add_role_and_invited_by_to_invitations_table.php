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
            $table->string('role')->default('employee')->after('email'); // employee or administration_office
            $table->foreignId('invited_by')->nullable()->after('employer_id')->constrained('users')->onDelete('cascade'); // Who sent the invitation
            $table->enum('invitation_type', ['new_account', 'company_access'])->default('new_account')->after('role'); // Type of invitation
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->dropForeign(['invited_by']);
            $table->dropColumn(['role', 'invited_by', 'invitation_type']);
        });
    }
};
