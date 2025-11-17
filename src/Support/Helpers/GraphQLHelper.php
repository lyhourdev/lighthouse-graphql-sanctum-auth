<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers;

use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;

/**
 * GraphQL Helper
 * 
 * Helper functions for GraphQL operations.
 * មុខងារជំនួយសម្រាប់ operations GraphQL។
 */
final class GraphQLHelper
{
    /**
     * Ensure the user is authenticated, throw error if not.
     * ធានាថា user បាន authenticate, throw error ប្រសិនបើមិនបាន។
     * 
     * @throws Error
     */
    public static function requireAuth(): void
    {
        if (! Auth::check()) {
            throw new Error('You must be authenticated to perform this action.');
        }
    }

    /**
     * Ensure the user has a specific role, throw error if not.
     * ធានាថា user មាន role ជាក់លាក់, throw error ប្រសិនបើមិនមាន។
     * 
     * @throws Error
     */
    public static function requireRole(string $role): void
    {
        self::requireAuth();

        if (! AuthHelper::hasRole($role)) {
            throw new Error("You must have the '{$role}' role to perform this action.");
        }
    }

    /**
     * Ensure the user has a specific permission, throw error if not.
     * ធានាថា user មាន permission ជាក់លាក់, throw error ប្រសិនបើមិនមាន។
     * 
     * @throws Error
     */
    public static function requirePermission(string $permission): void
    {
        self::requireAuth();

        if (! AuthHelper::can($permission)) {
            throw new Error("You must have the '{$permission}' permission to perform this action.");
        }
    }

    /**
     * Ensure the user has any of the given roles, throw error if not.
     * ធានាថា user មាន role ណាមួយក្នុង roles ដែលបានផ្តល់, throw error ប្រសិនបើមិនមាន។
     * 
     * @param array<int, string> $roles
     * @throws Error
     */
    public static function requireAnyRole(array $roles): void
    {
        self::requireAuth();

        if (! AuthHelper::hasAnyRole($roles)) {
            $rolesList = implode(', ', $roles);
            throw new Error("You must have one of the following roles: {$rolesList}");
        }
    }

    /**
     * Ensure the user has any of the given permissions, throw error if not.
     * ធានាថា user មាន permission ណាមួយក្នុង permissions ដែលបានផ្តល់, throw error ប្រសិនបើមិនមាន។
     * 
     * @param array<int, string> $permissions
     * @throws Error
     */
    public static function requireAnyPermission(array $permissions): void
    {
        self::requireAuth();

        if (! AuthHelper::canAny($permissions)) {
            $permissionsList = implode(', ', $permissions);
            throw new Error("You must have one of the following permissions: {$permissionsList}");
        }
    }

    /**
     * Ensure the user owns the resource, throw error if not.
     * ធានាថា user ជាម្ចាស់ resource, throw error ប្រសិនបើមិនមែន។
     * 
     * @param mixed $resource
     * @param string $relationField
     * @throws Error
     */
    public static function requireOwnership(mixed $resource, string $relationField = 'user_id'): void
    {
        self::requireAuth();

        $user = Auth::user();
        $userId = $user->getKey();

        if (is_object($resource) && method_exists($resource, 'getAttribute')) {
            $ownerId = $resource->getAttribute($relationField);

            if ($ownerId === null) {
                throw new Error("Resource does not have an owner field '{$relationField}'.");
            }

            if ((string) $ownerId !== (string) $userId) {
                throw new Error('You do not own this resource.');
            }
        } else {
            throw new Error('Invalid resource provided.');
        }
    }
}

