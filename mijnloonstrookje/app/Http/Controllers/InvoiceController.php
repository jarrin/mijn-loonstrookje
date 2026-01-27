<?php


namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices for the authenticated employer.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get company
        $company = $user->company;
        
        if (!$company) {
            return redirect()->route('employer.dashboard')
                ->with('error', 'Geen bedrijf gevonden.');
        }
        
        // Get all invoices for this company, ordered by newest first
        $invoices = $company->invoices()
            ->with(['subscription', 'customSubscription'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('employer.invoices.index', compact('invoices'));
    }

    /**
     * Display the specified invoice.
     */
    public function show(Invoice $invoice)
    {
        $user = Auth::user();
        
        // Ensure the invoice belongs to the user's company
        if ($invoice->company_id !== $user->company_id) {
            abort(403, 'Unauthorized access to this invoice.');
        }
        
        // Load relationships
        $invoice->load(['company', 'subscription', 'customSubscription']);
        
        return view('employer.invoices.show', compact('invoice'));
    }
}
