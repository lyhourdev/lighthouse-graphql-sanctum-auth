<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

/**
 * Device Model
 * 
 * Represents a device used for authentication.
 * តំណាង device ដែលប្រើសម្រាប់ authentication។
 */
class Device extends Model
{
    /**
     * The attributes that are mass assignable.
     * Attributes ដែលអាច mass assign។
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'token_id',
        'ip_address',
        'user_agent',
        'last_used_at',
        'is_active',
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
            'last_used_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the user that owns the device.
     * ទទួល user ដែលជាម្ចាស់ device។
     */
    public function user(): BelongsTo
    {
        $userModel = Auth::getProvider()->getModel();

        return $this->belongsTo($userModel, 'user_id');
    }

    /**
     * Scope a query to only include active devices.
     * Scope query ដើម្បីរួមបញ្ចូលតែ devices ដែល active។
     */
    public function scopeActive($query): void
    {
        $query->where('is_active', true);
    }

    /**
     * Scope a query to only include devices for a specific user.
     * Scope query ដើម្បីរួមបញ្ចូលតែ devices សម្រាប់ user ជាក់លាក់។
     */
    public function scopeForUser($query, int|string $userId): void
    {
        $query->where('user_id', $userId);
    }

    /**
     * Mark device as active.
     * សម្គាល់ device ថាជា active។
     */
    public function activate(): self
    {
        $this->update(['is_active' => true, 'last_used_at' => now()]);

        return $this;
    }

    /**
     * Mark device as inactive.
     * សម្គាល់ device ថាជា inactive។
     */
    public function deactivate(): self
    {
        $this->update(['is_active' => false]);

        return $this;
    }

    /**
     * Update last used timestamp.
     * ធ្វើបច្ចុប្បន្នភាព last used timestamp។
     */
    public function touchLastUsed(): self
    {
        $this->update(['last_used_at' => now()]);

        return $this;
    }
}

