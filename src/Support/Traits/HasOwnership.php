<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Traits;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

/**
 * Has Ownership Trait
 * 
 * Provides ownership-related functionality for models.
 * ផ្តល់ functionality ទាក់ទង ownership សម្រាប់ models។
 */
trait HasOwnership
{
    /**
     * Get the owner ID for this model.
     * ទទួល owner ID សម្រាប់ model នេះ។
     */
    public function getOwnerId(): ?string
    {
        return $this->getAttribute($this->getOwnerKey());
    }

    /**
     * Get the owner key name.
     * ទទួលឈ្មោះ owner key។
     */
    public function getOwnerKey(): string
    {
        return $this->ownerKey ?? 'user_id';
    }

    /**
     * Set the owner ID for this model.
     * កំណត់ owner ID សម្រាប់ model នេះ។
     */
    public function setOwnerId(?string $ownerId): self
    {
        $this->setAttribute($this->getOwnerKey(), $ownerId);

        return $this;
    }

    /**
     * Scope a query to only include records owned by a specific user.
     * Scope query ដើម្បីរួមបញ្ចូលតែ records ដែលជាកម្មសិទ្ធិរបស់ user ជាក់លាក់។
     */
    public function scopeOwnedBy($query, Authenticatable|string|int $user): void
    {
        $userId = $user instanceof Authenticatable ? $user->getKey() : $user;

        $query->where($this->getOwnerKey(), $userId);
    }

    /**
     * Scope a query to only include records owned by the authenticated user.
     * Scope query ដើម្បីរួមបញ្ចូលតែ records ដែលជាកម្មសិទ្ធិរបស់ user ដែលបាន authenticate។
     */
    public function scopeOwnedByCurrentUser($query): void
    {
        $user = Auth::user();

        if ($user !== null) {
            $query->ownedBy($user);
        }
    }

    /**
     * Check if this model is owned by a specific user.
     * ពិនិត្យមើលថា model នេះជាកម្មសិទ្ធិរបស់ user ជាក់លាក់ឬទេ។
     */
    public function isOwnedBy(Authenticatable|string|int $user): bool
    {
        $userId = $user instanceof Authenticatable ? $user->getKey() : $user;
        $ownerId = $this->getOwnerId();

        if ($ownerId === null) {
            return false;
        }

        return (string) $ownerId === (string) $userId;
    }

    /**
     * Check if this model is owned by the authenticated user.
     * ពិនិត្យមើលថា model នេះជាកម្មសិទ្ធិរបស់ user ដែលបាន authenticateឬទេ។
     */
    public function isOwnedByCurrentUser(): bool
    {
        $user = Auth::user();

        if ($user === null) {
            return false;
        }

        return $this->isOwnedBy($user);
    }

    /**
     * Assign ownership to a user.
     * ផ្តល់ ownership ទៅ user។
     */
    public function assignTo(Authenticatable|string|int $user): self
    {
        $userId = $user instanceof Authenticatable ? $user->getKey() : $user;

        $this->setOwnerId((string) $userId);

        return $this;
    }
}

