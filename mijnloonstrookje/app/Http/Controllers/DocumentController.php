<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    /**
     * Show the upload form
     */
    public function create($employeeId = null)
    {
        $user = Auth::user();
        
        // Check authorization
        if (!in_array($user->role, ['employer', 'administration_office'])) {
            abort(403, 'Unauthorized access');
        }
        
        // Get employees from the same company
        $employees = User::where('role', 'employee')
            ->where('company_id', $user->company_id)
            ->orderBy('name')
            ->get();
        
        $selectedEmployee = null;
        if ($employeeId) {
            $selectedEmployee = User::where('id', $employeeId)
                ->where('role', 'employee')
                ->where('company_id', $user->company_id)
                ->first();
        }
        
        return view('documents.upload', compact('employees', 'selectedEmployee'));
    }
    
    /**
     * Store uploaded document
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Check authorization
        if (!in_array($user->role, ['employer', 'administration_office'])) {
            abort(403, 'Unauthorized access');
        }
        
        // Validation
        $validated = $request->validate([
            'employee_id' => 'required|exists:users,id',
            'document_type' => 'required|in:payslip,annual_statement,other',
            'period_type' => 'required|in:Maandelijks,Weekelijks,2-wekelijks,Jaarlijks',
            'year' => 'required|integer|min:2000|max:2100',
            'month' => 'nullable|integer|min:1|max:12',
            'week' => 'nullable|integer|min:1|max:53',
            'note' => 'nullable|string|max:500',
            'document' => 'required|file|mimes:pdf|max:10240', // Max 10MB
        ]);
        
        // Verify employee belongs to the same company
        $employee = User::where('id', $validated['employee_id'])
            ->where('role', 'employee')
            ->where('company_id', $user->company_id)
            ->firstOrFail();
        
        // Validate period fields based on period_type
        if ($validated['period_type'] === 'Maandelijks' && !$request->filled('month')) {
            return back()->withErrors(['month' => 'Maand is verplicht voor maandelijkse periode'])->withInput();
        }
        
        if (in_array($validated['period_type'], ['Weekelijks', '2-wekelijks']) && !$request->filled('week')) {
            return back()->withErrors(['week' => 'Week is verplicht voor wekelijkse periode'])->withInput();
        }
        
        $file = $request->file('document');
        $originalFilename = $file->getClientOriginalName();
        $fileSize = $file->getSize();
        
        // Generate unique file path
        $filename = time() . '_' . uniqid() . '.pdf';
        $filePath = 'documents/' . $user->company_id . '/' . $employee->id . '/' . $filename;
        
        // Store encrypted file
        Document::storeEncrypted($file, $filePath);
        
        // Create document record
        $document = Document::create([
            'employee_id' => $employee->id,
            'company_id' => $user->company_id,
            'uploader_id' => $user->id,
            'type' => $validated['document_type'],
            'file_path' => $filePath,
            'original_filename' => $originalFilename,
            'file_size' => $fileSize,
            'year' => $validated['year'],
            'month' => $validated['month'],
            'week' => $validated['week'],
            'period_type' => $validated['period_type'],
            'note' => $validated['note'],
        ]);
        
        return redirect()
            ->route('employer.employee.documents', $employee->id)
            ->with('success', 'Document succesvol geüpload');
    }
    
    /**
     * View document (inline in browser)
     */
    public function view($id)
    {
        $user = Auth::user();
        $document = Document::findOrFail($id);
        
        // Check authorization
        $this->authorizeDocument($user, $document);
        
        // Get decrypted content
        $content = $document->getDecryptedContent();
        
        if (!$content) {
            abort(404, 'Document niet gevonden');
        }
        
        return response($content)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $document->original_filename . '"');
    }
    
    /**
     * Download document
     */
    public function download($id)
    {
        $user = Auth::user();
        $document = Document::findOrFail($id);
        
        // Check authorization
        $this->authorizeDocument($user, $document);
        
        // Get decrypted content
        $content = $document->getDecryptedContent();
        
        if (!$content) {
            abort(404, 'Document niet gevonden');
        }
        
        return response($content)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $document->original_filename . '"');
    }
    
    /**
     * Delete document
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $document = Document::findOrFail($id);
        
        // Only uploader or super admin can delete
        if ($user->role !== 'super_admin' && $document->uploader_id !== $user->id) {
            abort(403, 'Je hebt geen toestemming om dit document te verwijderen');
        }
        
        // Verify user has access to this company
        if ($document->company_id !== $user->company_id && $user->role !== 'super_admin') {
            abort(403, 'Unauthorized access');
        }
        
        // Soft delete - set is_deleted to true and deleted_at timestamp
        $document->is_deleted = true;
        $document->deleted_at = now();
        $document->save();
        
        return back()->with('success', 'Document succesvol verwijderd');
    }
    
    /**
     * Show deleted documents
     */
    public function deleted()
    {
        $user = Auth::user();
        
        // Check authorization
        if (!in_array($user->role, ['employer', 'administration_office', 'super_admin'])) {
            abort(403, 'Unauthorized access');
        }
        
        // Get deleted documents (using withTrashed to include soft-deleted records)
        $query = Document::withTrashed()->where('is_deleted', true);
        
        if ($user->role !== 'super_admin') {
            $query->where('company_id', $user->company_id);
        }
        
        $documents = $query->with(['employee', 'uploader'])
                          ->orderBy('deleted_at', 'desc')
                          ->get();
        
        return view('documents.deleted', compact('documents'));
    }
    
    /**
     * Restore deleted document
     */
    public function restore($id)
    {
        $user = Auth::user();
        $document = Document::withTrashed()
                           ->where('id', $id)
                           ->where('is_deleted', true)
                           ->firstOrFail();
        
        // Only uploader or super admin can restore
        if ($user->role !== 'super_admin' && $document->uploader_id !== $user->id) {
            abort(403, 'Je hebt geen toestemming om dit document te herstellen');
        }
        
        // Verify user has access to this company
        if ($document->company_id !== $user->company_id && $user->role !== 'super_admin') {
            abort(403, 'Unauthorized access');
        }
        
        // Restore document
        $document->is_deleted = false;
        $document->deleted_at = null;
        $document->save();
        
        return back()->with('success', 'Document succesvol hersteld');
    }
    
    /**
     * Check if user is authorized to access document
     */
    private function authorizeDocument($user, $document)
    {
        // Super admin can access everything
        if ($user->role === 'super_admin') {
            return;
        }
        
        // Document must belong to user's company
        if ($document->company_id !== $user->company_id) {
            abort(403, 'Unauthorized access');
        }
        
        // Employee can only access their own documents
        if ($user->role === 'employee' && $document->employee_id !== $user->id) {
            abort(403, 'Je hebt geen toestemming om dit document te bekijken');
        }
        
        // Employer and administration office can access all documents in their company
        if (!in_array($user->role, ['employer', 'administration_office', 'employee'])) {
            abort(403, 'Unauthorized access');
        }
    }
}
