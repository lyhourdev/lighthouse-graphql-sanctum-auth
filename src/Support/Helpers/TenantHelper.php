<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Tenancy\TenantResolver;

/**
 * Tenant Helper
 * 
 * Helper functions for tenant operations.
 * មុខងារជំនួយសម្រាប់ operations tenant។
 */
final class TenantHelper
{
    /**
     * Get the current tenant ID.
     * ទទួល tenant ID បច្ចុប្បន្ន។
     */
    public static function currentTenantId(?Request $request = null): ?string
    {
        if (! config('lighthouse-sanctum-auth.tenancy.enabled', false)) {
            return null;
        }

        $resolver = app(TenantResolver::class);

        return $resolver->resolve($request ?? request());
    }

    /**
     * Check if multi-tenancy is enabled.
     * ពិនិត្យមើលថា multi-tenancy ត្រូវបានបើកឬទេ។
     */
    public static function isEnabled(): bool
    {
        return config('lighthouse-sanctum-auth.tenancy.enabled', false);
    }

    /**
     * Get tenant ID from the authenticated user.
     * ទទួល tenant ID ពី user ដែលបាន authenticate។
     */
    public static function getTenantIdFromUser(): ?string
    {
        $user = Auth::user();

        if ($user === null) {
            return null;
        }

        if (method_exists($user, 'getTenantId')) {
            return (string) $user->getTenantId();
        }

        return $user->getAttribute('tenant_id');
    }

    /**
     * Set tenant context for the current request.
     * កំណត់ tenant context សម្រាប់ request បច្ចុប្បន្ន។
     */
    public static function setTenantContext(?string $tenantId): void
    {
        if ($tenantId === null) {
            return;
        }

        app()->instance('current_tenant_id', $tenantId);
    }

    /**
     * Get tenant context from the application container.
     * ទទួល tenant context ពី application container។
     */
    public static function getTenantContext(): ?string
    {
        return app()->bound('current_tenant_id') ? app('current_tenant_id') : null;
    }

    /**
     * Check if the current user belongs to the specified tenant.
     * ពិនិត្យមើលថា user បច្ចុប្បន្នជាកម្មសិទ្ធិរបស់ tenant ដែលបានបញ្ជាក់ឬទេ។
     */
    public static function userBelongsToTenant(?string $tenantId): bool
    {
        if ($tenantId === null) {
            return false;
        }

        $userTenantId = self::getTenantIdFromUser();

        return $userTenantId !== null && (string) $userTenantId === (string) $tenantId;
    }

    /**
     * Ensure the current user belongs to the tenant.
     * ធានាថា user បច្ចុប្បន្នជាកម្មសិទ្ធិរបស់ tenant។
     * 
     * @throws \RuntimeException
     */
    public static function ensureUserBelongsToTenant(?string $tenantId): void
    {
        if (! self::userBelongsToTenant($tenantId)) {
            throw new \RuntimeException('User does not belong to the specified tenant.');
        }
    }
}

