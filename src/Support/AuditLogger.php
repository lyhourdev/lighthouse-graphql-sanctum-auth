<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\Support;

use Illuminate\Support\Facades\Log;

/**
 * Audit Logger
 * 
 * Logs audit events for compliance and security purposes.
 * កត់ត្រា audit events សម្រាប់ compliance និង security។
 */
final class AuditLogger
{
    /**
     * Log an audit event.
     * កត់ត្រា audit event។
     * 
     * @param array<string, mixed> $data
     */
    public function log(array $data): void
    {
        Log::channel('audit')->info('Audit Event', [
            'user_id' => $data['user_id'] ?? null,
            'action' => $data['action'] ?? 'unknown',
            'field' => $data['field'] ?? null,
            'ip_address' => $data['ip_address'] ?? null,
            'user_agent' => $data['user_agent'] ?? null,
            'timestamp' => $data['timestamp'] ?? now()->toIso8601String(),
            'metadata' => $data['metadata'] ?? [],
        ]);
    }
}

