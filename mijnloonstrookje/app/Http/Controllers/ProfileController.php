<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $company = $user->role === 'employer' ? $user->company : null;
        
        return view('profile.settings', [
            'user' => $user,
            'company' => $company
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Het huidige wachtwoord is onjuist.'],
            ]);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Wachtwoord succesvol bijgewerkt.');
    }

    public function updateBranding(Request $request)
    {
        $user = auth()->user();
        
        // Only employers can update branding
        if ($user->role !== 'employer' || !$user->company) {
            abort(403, 'Unauthorized access');
        }
        
        $request->validate([
            'primary_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg', 'max:2048'],
        ]);
        
        $company = $user->company;
        
        // Update primary color
        $company->primary_color = $request->primary_color;
        
        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($company->logo_path && Storage::disk('public')->exists($company->logo_path)) {
                Storage::disk('public')->delete($company->logo_path);
            }
            
            // Store new logo
            $path = $request->file('logo')->store('company-logos', 'public');
            $company->logo_path = $path;
        }
        
        $company->save();
        
        return back()->with('success', 'Bedrijfsinstellingen succesvol bijgewerkt.');
    }
}
