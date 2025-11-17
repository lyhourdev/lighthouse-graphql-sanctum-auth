<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

/**
 * Authentication Helper
 * 
 * Helper functions for authentication operations.
 * មុខងារជំនួយសម្រាប់ operations authentication។
 */
final class AuthHelper
{
    /**
     * Get the currently authenticated user.
     * ទទួល user ដែលបាន authenticate បច្ចុប្បន្ន។
     */
    public static function user(): ?Authenticatable
    {
        return Auth::user();
    }

    /**
     * Check if a user is authenticated.
     * ពិនិត្យមើលថា user បាន authenticate ឬទេ។
     */
    public static function check(): bool
    {
        return Auth::check();
    }

    /**
     * Get the authenticated user's ID.
     * ទទួល ID របស់ user ដែលបាន authenticate។
     */
    public static function id(): int|string|null
    {
        return Auth::id();
    }

    /**
     * Get the authenticated user or throw an exception.
     * ទទួល user ដែលបាន authenticate ឬ throw exception។
     * 
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public static function userOrFail(): Authenticatable
    {
        return Auth::user() ?? throw new \Illuminate\Auth\AuthenticationException('User not authenticated.');
    }

    /**
     * Check if the authenticated user has a specific role.
     * ពិនិត្យមើលថា user ដែលបាន authenticate មាន role ជាក់លាក់ឬទេ។
     */
    public static function hasRole(string $role): bool
    {
        $user = self::user();

        if ($user === null || ! method_exists($user, 'hasRole')) {
            return false;
        }

        return $user->hasRole($role);
    }

    /**
     * Check if the authenticated user has any of the given roles.
     * ពិនិត្យមើលថា user ដែលបាន authenticate មាន role ណាមួយក្នុង roles ដែលបានផ្តល់ឬទេ។
     * 
     * @param array<int, string> $roles
     */
    public static function hasAnyRole(array $roles): bool
    {
        $user = self::user();

        if ($user === null || ! method_exists($user, 'hasAnyRole')) {
            return false;
        }

        return $user->hasAnyRole($roles);
    }

    /**
     * Check if the authenticated user has all of the given roles.
     * ពិនិត្យមើលថា user ដែលបាន authenticate មាន roles ទាំងអស់ដែលបានផ្តល់ឬទេ។
     * 
     * @param array<int, string> $roles
     */
    public static function hasAllRoles(array $roles): bool
    {
        $user = self::user();

        if ($user === null || ! method_exists($user, 'hasAllRoles')) {
            return false;
        }

        return $user->hasAllRoles($roles);
    }

    /**
     * Check if the authenticated user has a specific permission.
     * ពិនិត្យមើលថា user ដែលបាន authenticate មាន permission ជាក់លាក់ឬទេ។
     */
    public static function can(string $permission): bool
    {
        $user = self::user();

        if ($user === null || ! method_exists($user, 'can')) {
            return false;
        }

        return $user->can($permission);
    }

    /**
     * Check if the authenticated user has any of the given permissions.
     * ពិនិត្យមើលថា user ដែលបាន authenticate មាន permission ណាមួយក្នុង permissions ដែលបានផ្តល់ឬទេ។
     * 
     * @param array<int, string> $permissions
     */
    public static function canAny(array $permissions): bool
    {
        $user = self::user();

        if ($user === null) {
            return false;
        }

        foreach ($permissions as $permission) {
            if (self::can($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the authenticated user has all of the given permissions.
     * ពិនិត្យមើលថា user ដែលបាន authenticate មាន permissions ទាំងអស់ដែលបានផ្តល់ឬទេ។
     * 
     * @param array<int, string> $permissions
     */
    public static function canAll(array $permissions): bool
    {
        $user = self::user();

        if ($user === null) {
            return false;
        }

        foreach ($permissions as $permission) {
            if (! self::can($permission)) {
                return false;
            }
        }

        return true;
    }
}

