<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Traits;

/**
 * Has Tenant Trait
 * 
 * Provides tenant-related functionality for models.
 * ផ្តល់ functionality ទាក់ទង tenant សម្រាប់ models។
 */
trait HasTenant
{
    /**
     * Get the tenant ID for this model.
     * ទទួល tenant ID សម្រាប់ model នេះ។
     */
    public function getTenantId(): ?string
    {
        return $this->getAttribute('tenant_id');
    }

    /**
     * Set the tenant ID for this model.
     * កំណត់ tenant ID សម្រាប់ model នេះ។
     */
    public function setTenantId(?string $tenantId): self
    {
        $this->setAttribute('tenant_id', $tenantId);

        return $this;
    }

    /**
     * Scope a query to only include records for a specific tenant.
     * Scope query ដើម្បីរួមបញ្ចូលតែ records សម្រាប់ tenant ជាក់លាក់។
     */
    public function scopeForTenant($query, ?string $tenantId): void
    {
        if ($tenantId !== null) {
            $query->where('tenant_id', $tenantId);
        }
    }

    /**
     * Scope a query to only include records for the current tenant.
     * Scope query ដើម្បីរួមបញ្ចូលតែ records សម្រាប់ tenant បច្ចុប្បន្ន។
     */
    public function scopeForCurrentTenant($query): void
    {
        $tenantId = app('current_tenant_id');

        if ($tenantId !== null) {
            $query->where('tenant_id', $tenantId);
        }
    }

    /**
     * Check if this model belongs to a specific tenant.
     * ពិនិត្យមើលថា model នេះជាកម្មសិទ្ធិរបស់ tenant ជាក់លាក់ឬទេ។
     */
    public function belongsToTenant(?string $tenantId): bool
    {
        if ($tenantId === null) {
            return false;
        }

        return (string) $this->getTenantId() === (string) $tenantId;
    }
}

