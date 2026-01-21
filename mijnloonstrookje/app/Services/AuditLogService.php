<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Request;

class AuditLogService
{
    /**
     * Log an action to the audit_logs table
     * 
     * @param string $action The action performed (e.g., 'login', 'document_uploaded')
     * @param string|null $description Additional description of the action
     * @param string|null $targetType The type of target (e.g., 'Document', 'User')
     * @param int|null $targetId The ID of the target
     * @param int|null $userId The user who performed the action (defaults to authenticated user)
     * @param int|null $companyId The company context (defaults to user's company)
     * @return AuditLog
     */
    public static function log(
        string $action,
        ?string $description = null,
        ?string $targetType = null,
        ?int $targetId = null,
        ?int $userId = null,
        ?int $companyId = null
    ): AuditLog {
        $user = auth()->user();
        
        // Use provided userId or fall back to authenticated user
        $userId = $userId ?? ($user ? $user->id : null);
        
        // Use provided companyId or fall back to user's company
        if (!$companyId && $user && $user->company_id) {
            $companyId = $user->company_id;
        }
        
        return AuditLog::create([
            'user_id' => $userId,
            'company_id' => $companyId,
            'action' => $action,
            'description' => $description,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'ip_address' => Request::ip(),
        ]);
    }
    
    /**
     * Log a login action
     */
    public static function logLogin(int $userId, int $companyId = null): AuditLog
    {
        return self::log(
            action: 'login',
            description: 'User logged in',
            userId: $userId,
            companyId: $companyId
        );
    }
    
    /**
     * Log a document upload
     */
    public static function logDocumentUpload(int $documentId, int $employeeId, int $companyId): AuditLog
    {
        return self::log(
            action: 'document_uploaded',
            description: 'Document uploaded for employee',
            targetType: 'Document',
            targetId: $documentId,
            companyId: $companyId
        );
    }
    
    /**
     * Log a document revision
     */
    public static function logDocumentRevision(int $documentId, int $companyId, float $version): AuditLog
    {
        return self::log(
            action: 'document_revised',
            description: "Document revised to version {$version}",
            targetType: 'Document',
            targetId: $documentId,
            companyId: $companyId
        );
    }
    
    /**
     * Log a document deletion
     */
    public static function logDocumentDeleted(int $documentId, int $companyId): AuditLog
    {
        return self::log(
            action: 'document_deleted',
            description: 'Document moved to trash',
            targetType: 'Document',
            targetId: $documentId,
            companyId: $companyId
        );
    }
    
    /**
     * Log a document restoration
     */
    public static function logDocumentRestored(int $documentId, int $companyId): AuditLog
    {
        return self::log(
            action: 'document_restored',
            description: 'Document restored from trash',
            targetType: 'Document',
            targetId: $documentId,
            companyId: $companyId
        );
    }
    
    /**
     * Log employee creation
     */
    public static function logEmployeeCreated(int $employeeId, int $companyId, int $invitedBy): AuditLog
    {
        return self::log(
            action: 'employee_created',
            description: 'New employee account created',
            targetType: 'User',
            targetId: $employeeId,
            userId: $invitedBy,
            companyId: $companyId
        );
    }
    
    /**
     * Log admin office addition
     */
    public static function logAdminOfficeAdded(int $adminOfficeId, int $companyId, int $invitedBy): AuditLog
    {
        return self::log(
            action: 'admin_office_added',
            description: 'Administration office access granted',
            targetType: 'User',
            targetId: $adminOfficeId,
            userId: $invitedBy,
            companyId: $companyId
        );
    }
}
