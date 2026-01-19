<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use ZipArchive;

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
        
        // Get employees based on user role
        if ($user->role === 'administration_office') {
            // Get company IDs that admin office has access to
            $companyIds = $user->companies()
                ->wherePivot('status', 'active')
                ->pluck('companies.id');
            
            $employees = User::where('role', 'employee')
                ->whereIn('company_id', $companyIds)
                ->orderBy('name')
                ->get();
        } else {
            // Employer: get employees from their company
            $employees = User::where('role', 'employee')
                ->where('company_id', $user->company_id)
                ->orderBy('name')
                ->get();
        }
        
        $selectedEmployee = null;
        if ($employeeId) {
            if ($user->role === 'administration_office') {
                $companyIds = $user->companies()
                    ->wherePivot('status', 'active')
                    ->pluck('companies.id');
                
                $selectedEmployee = User::where('id', $employeeId)
                    ->where('role', 'employee')
                    ->whereIn('company_id', $companyIds)
                    ->first();
            } else {
                $selectedEmployee = User::where('id', $employeeId)
                    ->where('role', 'employee')
                    ->where('company_id', $user->company_id)
                    ->first();
            }
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
        
        // Verify employee belongs to accessible company
        if ($user->role === 'administration_office') {
            $companyIds = $user->companies()
                ->wherePivot('status', 'active')
                ->pluck('companies.id');
            
            $employee = User::where('id', $validated['employee_id'])
                ->where('role', 'employee')
                ->whereIn('company_id', $companyIds)
                ->firstOrFail();
        } else {
            $employee = User::where('id', $validated['employee_id'])
                ->where('role', 'employee')
                ->where('company_id', $user->company_id)
                ->firstOrFail();
        }
        
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
        
        // Generate unique file path using employee's company_id
        $filename = time() . '_' . uniqid() . '.pdf';
        $filePath = 'documents/' . $employee->company_id . '/' . $employee->id . '/' . $filename;
        
        // Store encrypted file
        Document::storeEncrypted($file, $filePath);
        
        // Create document record
        $document = Document::create([
            'employee_id' => $employee->id,
            'company_id' => $employee->company_id,
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
        if ($user->role === 'administration_office') {
            $hasAccess = $user->companies()
                ->where('companies.id', $document->company_id)
                ->wherePivot('status', 'active')
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'Je hebt geen toegang tot dit bedrijf');
            }
        } elseif ($document->company_id !== $user->company_id && $user->role !== 'super_admin') {
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
        
        if ($user->role === 'administration_office') {
            // Admin office: get documents from accessible companies
            $companyIds = $user->companies()
                ->wherePivot('status', 'active')
                ->pluck('companies.id');
            $query->whereIn('company_id', $companyIds);
        } elseif ($user->role !== 'super_admin') {
            // Employer: only their company
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
        if ($user->role === 'administration_office') {
            $hasAccess = $user->companies()
                ->where('companies.id', $document->company_id)
                ->wherePivot('status', 'active')
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'Je hebt geen toegang tot dit bedrijf');
            }
        } elseif ($document->company_id !== $user->company_id && $user->role !== 'super_admin') {
            abort(403, 'Unauthorized access');
        }
        
        // Restore document
        $document->is_deleted = false;
        $document->deleted_at = null;
        $document->save();
        
        return back()->with('success', 'Document succesvol hersteld');
    }
    
    /**
     * Show edit form for document revision
     */
    public function edit($id)
    {
        $user = Auth::user();
        
        // Check authorization
        if (!in_array($user->role, ['employer', 'administration_office'])) {
            abort(403, 'Unauthorized access');
        }
        
        $document = Document::findOrFail($id);
        
        // Verify user has access to this company
        if ($user->role === 'administration_office') {
            $hasAccess = $user->companies()
                ->where('companies.id', $document->company_id)
                ->wherePivot('status', 'active')
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'Je hebt geen toegang tot dit bedrijf');
            }
        } elseif ($document->company_id !== $user->company_id && $user->role !== 'super_admin') {
            abort(403, 'Unauthorized access');
        }
        
        return view('documents.edit', compact('document'));
    }
    
    /**
     * Store new revision of document
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        // Check authorization
        if (!in_array($user->role, ['employer', 'administration_office'])) {
            abort(403, 'Unauthorized access');
        }
        
        $originalDocument = Document::findOrFail($id);
        
        // Verify user has access to this company
        if ($user->role === 'administration_office') {
            $hasAccess = $user->companies()
                ->where('companies.id', $originalDocument->company_id)
                ->wherePivot('status', 'active')
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'Je hebt geen toegang tot dit bedrijf');
            }
        } elseif ($originalDocument->company_id !== $user->company_id && $user->role !== 'super_admin') {
            abort(403, 'Unauthorized access');
        }
        
        // Validation
        $validated = $request->validate([
            'document' => 'required|file|mimes:pdf|max:10240', // Max 10MB
            'document_type' => 'required|in:payslip,annual_statement,other',
            'period_type' => 'required|in:Maandelijks,Weekelijks,2-wekelijks,Jaarlijks',
            'year' => 'required|integer|min:2000|max:2100',
            'month' => 'nullable|integer|min:1|max:12',
            'week' => 'nullable|integer|min:1|max:53',
            'note' => 'nullable|string|max:500',
        ]);
        
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
        
        // Determine parent and new version
        $parentId = $originalDocument->parent_document_id ?? $originalDocument->id;
        $parent = $originalDocument->parent_document_id ? $originalDocument->parentDocument : $originalDocument;
        
        // Get highest version number in this revision chain
        $maxVersion = Document::where('parent_document_id', $parentId)
                             ->orWhere('id', $parentId)
                             ->max('version');
        
        // Calculate new version: 1.0 -> 1.1 -> 1.2 ... -> 1.9 -> 2.0
        $majorVersion = floor($maxVersion);
        $minorVersion = ($maxVersion - $majorVersion) * 10;
        
        if ($minorVersion >= 9) {
            // Go to next major version
            $newVersion = $majorVersion + 1.0;
        } else {
            // Increment minor version
            $newVersion = $majorVersion + (($minorVersion + 1) / 10);
        }
        
        // Generate unique file path
        $filename = time() . '_' . uniqid() . '.pdf';
        $filePath = 'documents/' . $user->company_id . '/' . $originalDocument->employee_id . '/' . $filename;
        
        // Store encrypted file
        Document::storeEncrypted($file, $filePath);
        
        // Create new document revision
        $newDocument = Document::create([
            'employee_id' => $originalDocument->employee_id,
            'company_id' => $originalDocument->company_id,
            'uploader_id' => $user->id,
            'type' => $validated['document_type'],
            'file_path' => $filePath,
            'original_filename' => $originalFilename,
            'file_size' => $fileSize,
            'year' => $validated['year'],
            'month' => $validated['month'],
            'week' => $validated['week'],
            'period_type' => $validated['period_type'],
            'version' => $newVersion,
            'parent_document_id' => $parentId,
            'note' => $validated['note'],
        ]);
        
        return redirect()
            ->route('employer.employee.documents', $originalDocument->employee_id)
            ->with('success', "Document succesvol bijgewerkt naar versie " . number_format($newVersion, 1));
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
        
        // Administration office: check if they have access to the company via pivot table
        if ($user->role === 'administration_office') {
            $hasAccess = $user->companies()
                ->where('companies.id', $document->company_id)
                ->wherePivot('status', 'active')
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'Je hebt geen toegang tot documenten van dit bedrijf');
            }
            return;
        }
        
        // For other roles: Document must belong to user's company
        if ($document->company_id !== $user->company_id) {
            abort(403, 'Unauthorized access');
        }
        
        // Employee can only access their own documents
        if ($user->role === 'employee' && $document->employee_id !== $user->id) {
            abort(403, 'Je hebt geen toestemming om dit document te bekijken');
        }
        
        // Employer can access all documents in their company
        if (!in_array($user->role, ['employer', 'employee'])) {
            abort(403, 'Unauthorized access');
        }
    }

    /**
     * Bulk download documents as ZIP
     */
    public function bulkDownload(Request $request)
    {
        $user = Auth::user();
        
        // Validate request
        $validated = $request->validate([
            'type' => 'required|string|in:all,payslip,annual_statement,other',
            'year' => 'required|string',
        ]);
        
        // Build query based on user role
        $query = Document::where('is_deleted', false);
        
        if ($user->role === 'employee') {
            // Employee: only their own documents
            $query->where('employee_id', $user->id);
        } elseif ($user->role === 'employer') {
            // Employer: all documents in their company
            $query->where('company_id', $user->company_id);
        } elseif ($user->role === 'administration_office') {
            // Admin office: documents from accessible companies
            $companyIds = $user->companies()
                ->wherePivot('status', 'active')
                ->pluck('companies.id');
            $query->whereIn('company_id', $companyIds);
        }
        
        // Apply filters
        if ($validated['type'] !== 'all') {
            $query->where('type', $validated['type']);
        }
        
        if ($validated['year'] !== 'all') {
            $query->where('year', $validated['year']);
        }
        
        $documents = $query->with(['employee', 'company'])->get();
        
        if ($documents->isEmpty()) {
            return back()->with('error', 'Geen documenten gevonden met de geselecteerde filters');
        }
        
        // Create temporary ZIP file
        $zipFileName = 'documenten_' . date('Y-m-d_His') . '.zip';
        $zipFilePath = storage_path('app/temp/' . $zipFileName);
        
        // Ensure temp directory exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }
        
        $zip = new ZipArchive();
        
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'Kon ZIP-bestand niet aanmaken');
        }
        
        // Add documents to ZIP
        foreach ($documents as $document) {
            try {
                // Decrypt and get file content
                $decryptedContent = $document->getDecryptedContent();
                
                // Create filename with employee name and period
                $fileName = $document->employee->name . '_' . 
                           $document->display_name . '_' . 
                           $document->year;
                
                if ($document->month) {
                    $fileName .= '_' . str_pad($document->month, 2, '0', STR_PAD_LEFT);
                } elseif ($document->week) {
                    $fileName .= '_week' . $document->week;
                }
                
                $fileName .= '.pdf';
                
                // Sanitize filename
                $fileName = preg_replace('/[^A-Za-z0-9_\-.]/', '_', $fileName);
                
                // Add to ZIP
                $zip->addFromString($fileName, $decryptedContent);
            } catch (\Exception $e) {
                // Continue with other files if one fails
                continue;
            }
        }
        
        $zip->close();
        
        // Download and delete temp file
        return response()->download($zipFilePath, $zipFileName)->deleteFileAfterSend(true);
    }
}
