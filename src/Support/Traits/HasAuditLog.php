<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Has Audit Log Trait
 * 
 * Provides audit logging functionality for models.
 * ផ្តល់ audit logging functionality សម្រាប់ models។
 */
trait HasAuditLog
{
    /**
     * Boot the trait.
     * Boot trait។
     */
    public static function bootHasAuditLog(): void
    {
        if (! config('lighthouse-sanctum-auth.audit.enabled', true)) {
            return;
        }

        static::created(function ($model): void {
            $model->logAuditEvent('created', $model->getAttributes());
        });

        static::updated(function ($model): void {
            $model->logAuditEvent('updated', [
                'old' => $model->getOriginal(),
                'new' => $model->getChanges(),
            ]);
        });

        static::deleted(function ($model): void {
            $model->logAuditEvent('deleted', $model->getAttributes());
        });
    }

    /**
     * Log an audit event.
     * កត់ត្រា audit event។
     * 
     * @param array<string, mixed> $data
     */
    public function logAuditEvent(string $action, array $data = []): void
    {
        if (! config('lighthouse-sanctum-auth.audit.enabled', true)) {
            return;
        }

        $logData = [
            'model' => static::class,
            'model_id' => $this->getKey(),
            'action' => $action,
            'user_id' => Auth::id(),
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
            'data' => $data,
            'timestamp' => now()->toIso8601String(),
        ];

        Log::channel('audit')->info('Model Audit Event', $logData);
    }

    /**
     * Get audit log data for this model.
     * ទទួល audit log data សម្រាប់ model នេះ។
     * 
     * @return array<string, mixed>
     */
    public function getAuditData(): array
    {
        return [
            'model' => static::class,
            'model_id' => $this->getKey(),
            'attributes' => $this->getAttributes(),
        ];
    }
}

