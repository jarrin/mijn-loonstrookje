<?php
// Dummy invitation generator for test mails
namespace App\Console\Commands;

use App\Models\Invitation;
use App\Models\User;
use App\Models\Company;

class DummyInvitationFactory
{
    public static function make($user, $company, $role = 'employee', $type = 'employee')
    {
        return new Invitation([
            'email' => $user->email ?? 'test@example.com',
            'token' => 'testtoken-' . uniqid(),
            'employer_id' => $user->id ?? 1,
            'company_id' => $company->id ?? 1,
            'invited_by' => $user->id ?? 1,
            'role' => $role,
            'invitation_type' => $type,
            'status' => 'pending',
            'expires_at' => now()->addDays(7),
        ]);
    }
}
