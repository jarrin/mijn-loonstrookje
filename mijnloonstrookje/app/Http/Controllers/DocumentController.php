<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Services\AuditLogService;
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
        
        $selectedEmployee = null;
        $company = null;
        
        // Check if coming from company context via query parameter
        $companyId = request()->query('company');
        
        // Check if employee is pre-selected
        if ($employeeId) {
            if ($user->role === 'administration_office') {
                $companyIds = $user->companies()
                    ->wherePivot('status', 'active')
                    ->pluck('companies.id');
                
                $selectedEmployee = User::where('id', $employeeId)
                    ->where('role', 'employee')
                    ->whereIn('company_id', $companyIds)
                    ->first();
                
                // Set company for branding if employee is selected
                if ($selectedEmployee) {
                    $company = $selectedEmployee->company;
                }
            } else {
                $selectedEmployee = User::where('id', $employeeId)
                    ->where('role', 'employee')
                    ->where('company_id', $user->company_id)
                    ->first();
            }
        }
        // Check if company ID provided via query parameter (from company documents page)
        elseif ($companyId && $user->role === 'administration_office') {
            $company = $user->companies()
                ->wherePivot('status', 'active')
                ->where('companies.id', $companyId)
                ->first();
        }
        
        // Get employees based on user role and context
        if ($user->role === 'administration_office') {
            // If coming from a specific company context
            if ($company) {
                // Only show employees from that specific company
                $employees = User::where('role', 'employee')
                    ->where('company_id', $company->id)
                    ->orderBy('name')
                    ->get();
            } else {
                // Show all employees from accessible companies
                $companyIds = $user->companies()
                    ->wherePivot('status', 'active')
                    ->pluck('companies.id');
                
                $employees = User::where('role', 'employee')
                    ->whereIn('company_id', $companyIds)
                    ->orderBy('name')
                    ->get();
            }
        } else {
            // Employer: get employees from their company
            $employees = User::where('role', 'employee')
                ->where('company_id', $user->company_id)
                ->orderBy('name')
                ->get();
        }
        
        // Determine cancel URL based on context
        $cancelUrl = null;
        if ($selectedEmployee) {
            // Coming from specific employee documents page
            $cancelUrl = route('employer.employee.documents', $selectedEmployee->id);
        } elseif ($company && $user->role === 'administration_office') {
            // Coming from company documents page
            $cancelUrl = route('administration.company.documents', $company->id);
        } elseif ($user->role === 'administration_office') {
            // Coming from global admin office documents page
            $cancelUrl = route('administration.documents');
        } elseif ($user->role === 'employer') {
            // Coming from employer documents page
            $cancelUrl = route('employer.documents');
        } else {
            // Fallback
            $cancelUrl = route('employer.employees');
        }
        
        return view('documents.upload', compact('employees', 'selectedEmployee', 'company', 'cancelUrl'));
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
        
        // Log the document upload
        AuditLogService::logDocumentUpload($document->id, $employee->id, $employee->company_id);
        
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
        
        // Authorization check based on role
        if ($user->role === 'super_admin') {
            // Super admin can delete anything
        } elseif ($user->role === 'employer') {
            // Employers can delete any document in their company
            if ($document->company_id !== $user->company_id) {
                abort(403, 'Je hebt geen toestemming om dit document te verwijderen');
            }
        } elseif ($user->role === 'administration_office') {
            // Admin office can only delete documents they uploaded in companies they have access to
            if ($document->uploader_id !== $user->id) {
                abort(403, 'Je hebt geen toestemming om dit document te verwijderen');
            }
            
            $hasAccess = $user->companies()
                ->where('companies.id', $document->company_id)
                ->wherePivot('status', 'active')
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'Je hebt geen toegang tot dit bedrijf');
            }
        } else {
            abort(403, 'Je hebt geen toestemming om dit document te verwijderen');
        }
        
        // Soft delete - set is_deleted to true and deleted_at timestamp
        $document->is_deleted = true;
        $document->deleted_at = now();
        $document->save();
        
        // Log the document deletion
        AuditLogService::logDocumentDeleted($document->id, $document->company_id);
        
        return back()->with('success', 'Document succesvol verwijderd');
    }
    
    /**
     * Show deleted documents
     */
    public function deleted(Request $request)
    {
        $user = Auth::user();
        
        // Check authorization
        if (!in_array($user->role, ['employer', 'administration_office', 'super_admin'])) {
            abort(403, 'Unauthorized access');
        }
        
        // Check for employee or company context
        $employee = null;
        $company = null;
        $employeeId = $request->query('employee');
        $companyId = $request->query('company');
        
        // Get deleted documents (using withTrashed to include soft-deleted records)
        $query = Document::withTrashed()->where('is_deleted', true);
        
        // If specific employee requested
        if ($employeeId) {
            if ($user->role === 'administration_office') {
                $companyIds = $user->companies()
                    ->wherePivot('status', 'active')
                    ->pluck('companies.id');
                $employee = User::where('id', $employeeId)
                    ->where('role', 'employee')
                    ->whereIn('company_id', $companyIds)
                    ->firstOrFail();
            } else {
                $employee = User::where('id', $employeeId)
                    ->where('role', 'employee')
                    ->where('company_id', $user->company_id)
                    ->firstOrFail();
            }
            $query->where('employee_id', $employee->id);
            $company = $employee->company;
        }
        // If specific company requested (admin office viewing company documents)
        elseif ($companyId && $user->role === 'administration_office') {
            $company = $user->companies()
                ->wherePivot('status', 'active')
                ->where('companies.id', $companyId)
                ->firstOrFail();
            $query->where('company_id', $company->id);
        }
        // Default filtering by role
        elseif ($user->role === 'administration_office') {
            // Admin office: get documents from accessible companies
            $companyIds = $user->companies()
                ->wherePivot('status', 'active')
                ->pluck('companies.id');
            $query->whereIn('company_id', $companyIds);
        } elseif ($user->role !== 'super_admin') {
            // Employer: only their company
            $query->where('company_id', $user->company_id);
            $company = $user->company;
        }
        
        $documents = $query->with(['employee', 'uploader'])
                          ->orderBy('deleted_at', 'desc')
                          ->get();
        
        return view('documents.deleted', compact('documents', 'employee', 'company'));
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
        
        // Authorization check based on role
        if ($user->role === 'super_admin') {
            // Super admin can restore anything
        } elseif ($user->role === 'employer') {
            // Employers can restore any document in their company
            if ($document->company_id !== $user->company_id) {
                abort(403, 'Je hebt geen toestemming om dit document te herstellen');
            }
        } elseif ($user->role === 'administration_office') {
            // Admin office can only restore documents they uploaded in companies they have access to
            if ($document->uploader_id !== $user->id) {
                abort(403, 'Je hebt geen toestemming om dit document te herstellen');
            }
            
            $hasAccess = $user->companies()
                ->where('companies.id', $document->company_id)
                ->wherePivot('status', 'active')
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'Je hebt geen toegang tot dit bedrijf');
            }
        } else {
            abort(403, 'Je hebt geen toestemming om dit document te herstellen');
        }
        
        // Restore document
        $document->is_deleted = false;
        $document->deleted_at = null;
        $document->save();
        
        // Log the document restoration
        AuditLogService::logDocumentRestored($document->id, $document->company_id);
        
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
        
        // Log the document revision
        AuditLogService::logDocumentRevision($newDocument->id, $newDocument->company_id, $newVersion);
        
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
