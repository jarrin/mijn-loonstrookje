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
            action: 'inloggen',
            description: 'Gebruiker is ingelogd',
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
            action: 'document_geupload',
            description: 'Document geüpload voor medewerker',
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
            action: 'document_herzien',
            description: "Document herzien naar versie {$version}",
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
            action: 'document_verwijderd',
            description: 'Document verplaatst naar prullenbak',
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
            action: 'document_hersteld',
            description: 'Document hersteld uit prullenbak',
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
            action: 'medewerker_aangemaakt',
            description: 'Nieuw medewerker account aangemaakt',
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
            action: 'admin_bureau_toegevoegd',
            description: 'Administratiekantoor toegang verleend',
            targetType: 'User',
            targetId: $adminOfficeId,
            userId: $invitedBy,
            companyId: $companyId
        );
    }
    
    /**
     * Log user status change
     */
    public static function logUserStatusChange(int $targetUserId, string $oldStatus, string $newStatus, ?int $companyId = null): AuditLog
    {
        $oldStatusLabel = match($oldStatus) {
            'active' => 'actief',
            'inactive' => 'inactief',
            default => $oldStatus
        };
        
        $newStatusLabel = match($newStatus) {
            'active' => 'actief',
            'inactive' => 'inactief',
            default => $newStatus
        };
        
        return self::log(
            action: 'gebruiker_status_gewijzigd',
            description: "Gebruiker status gewijzigd van {$oldStatusLabel} naar {$newStatusLabel}",
            targetType: 'User',
            targetId: $targetUserId,
            companyId: $companyId
        );
    }
    
    /**
     * Log failed login attempt for inactive user
     */
    public static function logInactiveLoginAttempt(int $userId, ?int $companyId = null): AuditLog
    {
        return self::log(
            action: 'inactief_inlogpoging',
            description: 'Inactieve gebruiker probeerde in te loggen',
            targetType: 'User',
            targetId: $userId,
            userId: $userId,
            companyId: $companyId
        );
    }
}
