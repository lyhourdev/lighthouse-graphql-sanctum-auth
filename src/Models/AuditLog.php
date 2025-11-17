<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Audit Log Model
 * 
 * Represents an audit log entry.
 * តំណាង audit log entry។
 */
class AuditLog extends Model
{
    /**
     * The attributes that are mass assignable.
     * Attributes ដែលអាច mass assign។
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'action',
        'auditable_type',
        'auditable_id',
        'ip_address',
        'user_agent',
        'data',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     * Attributes ដែលត្រូវ cast។
     * 
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'data' => 'array',
            'metadata' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the auditable model.
     * ទទួល auditable model។
     */
    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include logs for a specific action.
     * Scope query ដើម្បីរួមបញ្ចូលតែ logs សម្រាប់ action ជាក់លាក់។
     */
    public function scopeForAction($query, string $action): void
    {
        $query->where('action', $action);
    }

    /**
     * Scope a query to only include logs for a specific user.
     * Scope query ដើម្បីរួមបញ្ចូលតែ logs សម្រាប់ user ជាក់លាក់។
     */
    public function scopeForUser($query, int|string $userId): void
    {
        $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include logs for a specific model.
     * Scope query ដើម្បីរួមបញ្ចូលតែ logs សម្រាប់ model ជាក់លាក់។
     */
    public function scopeForModel($query, string $modelType, int|string $modelId): void
    {
        $query->where('auditable_type', $modelType)
            ->where('auditable_id', $modelId);
    }

    /**
     * Scope a query to only include logs within a date range.
     * Scope query ដើម្បីរួមបញ្ចូលតែ logs ក្នុងចន្លោះកាលបរិច្ឆេទ។
     */
    public function scopeInDateRange($query, $startDate, $endDate): void
    {
        $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}

