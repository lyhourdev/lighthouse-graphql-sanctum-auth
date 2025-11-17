<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Permission Helper
 * 
 * Helper functions for role and permission operations.
 * មុខងារជំនួយសម្រាប់ operations roles និង permissions។
 */
final class PermissionHelper
{
    /**
     * Find or create a role.
     * រក ឬបង្កើត role។
     */
    public static function findOrCreateRole(string $name, string $guardName = 'web'): Role
    {
        return Role::firstOrCreate(
            ['name' => $name, 'guard_name' => $guardName],
            ['name' => $name, 'guard_name' => $guardName]
        );
    }

    /**
     * Find or create a permission.
     * រក ឬបង្កើត permission។
     */
    public static function findOrCreatePermission(string $name, string $guardName = 'web'): Permission
    {
        return Permission::firstOrCreate(
            ['name' => $name, 'guard_name' => $guardName],
            ['name' => $name, 'guard_name' => $guardName]
        );
    }

    /**
     * Assign a role to a user.
     * ផ្តល់ role ទៅ user។
     */
    public static function assignRoleToUser(Authenticatable $user, string|Role $role): void
    {
        if (is_string($role)) {
            $role = self::findOrCreateRole($role);
        }

        $user->assignRole($role);
    }

    /**
     * Remove a role from a user.
     * ដក role ពី user។
     */
    public static function removeRoleFromUser(Authenticatable $user, string|Role $role): void
    {
        if (is_string($role)) {
            $role = Role::findByName($role);
        }

        if ($role instanceof Role) {
            $user->removeRole($role);
        }
    }

    /**
     * Give a permission to a user.
     * ផ្តល់ permission ទៅ user។
     */
    public static function givePermissionToUser(Authenticatable $user, string|Permission $permission): void
    {
        if (is_string($permission)) {
            $permission = self::findOrCreatePermission($permission);
        }

        $user->givePermissionTo($permission);
    }

    /**
     * Revoke a permission from a user.
     * ដក permission ពី user។
     */
    public static function revokePermissionFromUser(Authenticatable $user, string|Permission $permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::findByName($permission);
        }

        if ($permission instanceof Permission) {
            $user->revokePermissionTo($permission);
        }
    }

    /**
     * Give a permission to a role.
     * ផ្តល់ permission ទៅ role។
     */
    public static function givePermissionToRole(string|Role $role, string|Permission $permission): void
    {
        if (is_string($role)) {
            $role = self::findOrCreateRole($role);
        }

        if (is_string($permission)) {
            $permission = self::findOrCreatePermission($permission);
        }

        $role->givePermissionTo($permission);
    }

    /**
     * Revoke a permission from a role.
     * ដក permission ពី role។
     */
    public static function revokePermissionFromRole(string|Role $role, string|Permission $permission): void
    {
        if (is_string($role)) {
            $role = Role::findByName($role);
        }

        if (is_string($permission)) {
            $permission = Permission::findByName($permission);
        }

        if ($role instanceof Role && $permission instanceof Permission) {
            $role->revokePermissionTo($permission);
        }
    }

    /**
     * Sync roles for a user (removes all existing and assigns new ones).
     * ធ្វើសមកាល roles សម្រាប់ user (លុប existing ទាំងអស់ និងផ្តល់ថ្មី)។
     * 
     * @param array<int, string|Role> $roles
     */
    public static function syncRolesForUser(Authenticatable $user, array $roles): void
    {
        $roleModels = [];

        foreach ($roles as $role) {
            if (is_string($role)) {
                $roleModels[] = self::findOrCreateRole($role);
            } elseif ($role instanceof Role) {
                $roleModels[] = $role;
            }
        }

        $user->syncRoles($roleModels);
    }

    /**
     * Sync permissions for a user (removes all existing and assigns new ones).
     * ធ្វើសមកាល permissions សម្រាប់ user (លុប existing ទាំងអស់ និងផ្តល់ថ្មី)។
     * 
     * @param array<int, string|Permission> $permissions
     */
    public static function syncPermissionsForUser(Authenticatable $user, array $permissions): void
    {
        $permissionModels = [];

        foreach ($permissions as $permission) {
            if (is_string($permission)) {
                $permissionModels[] = self::findOrCreatePermission($permission);
            } elseif ($permission instanceof Permission) {
                $permissionModels[] = $permission;
            }
        }

        $user->syncPermissions($permissionModels);
    }

    /**
     * Get all roles for the authenticated user.
     * ទទួល roles ទាំងអស់សម្រាប់ user ដែលបាន authenticate។
     */
    public static function getUserRoles(): \Illuminate\Database\Eloquent\Collection
    {
        $user = Auth::user();

        return $user?->roles ?? collect();
    }

    /**
     * Get all permissions for the authenticated user.
     * ទទួល permissions ទាំងអស់សម្រាប់ user ដែលបាន authenticate។
     */
    public static function getUserPermissions(): \Illuminate\Database\Eloquent\Collection
    {
        $user = Auth::user();

        if ($user === null || ! method_exists($user, 'getAllPermissions')) {
            return collect();
        }

        return $user->getAllPermissions();
    }
}

