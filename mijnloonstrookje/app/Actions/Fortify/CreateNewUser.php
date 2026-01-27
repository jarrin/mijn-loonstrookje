<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'kvk_number' => [
                'required',
                'string',
                'size:8',
                'regex:/^[0-9]{8}$/',
                Rule::unique(Company::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        // Maak eerst het bedrijf aan
        $company = Company::create([
            'name' => $input['name'] . "'s Bedrijf",
            'email' => $input['email'],
            'kvk_number' => $input['kvk_number'],
            'subscription_id' => null, // Wordt later gezet na betaling
        ]);

        // Maak de gebruiker aan als werkgever
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => 'employer', // Altijd employer bij publieke registratie
            'company_id' => $company->id,
            'status' => 'active',
        ]);

        // Sla subscription_id op in sessie als die meegegeven is
        if (isset($input['subscription_id'])) {
            session(['pending_subscription_id' => $input['subscription_id']]);
        }

        // Factuur direct genereren na account aanmaak
        \App\Models\Invoice::create([
            'company_id' => $company->id,
            'invoice_number' => \App\Models\Invoice::generateInvoiceNumber(),
            'amount' => 0, // Zet hier het juiste bedrag indien bekend
            'description' => 'Startfactuur bij registratie',
            'status' => 'pending',
            'issued_date' => now(),
            'due_date' => now()->addDays(14),
        ]);

        return $user;
    }
}
