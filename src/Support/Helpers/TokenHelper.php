<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * Token Helper
 * 
 * Helper functions for token operations.
 * មុខងារជំនួយសម្រាប់ operations tokens។
 */
final class TokenHelper
{
    /**
     * Create a token for the authenticated user.
     * បង្កើត token សម្រាប់ user ដែលបាន authenticate។
     * 
     * @param array<int, string> $abilities
     */
    public static function createToken(
        Authenticatable $user,
        string $name,
        array $abilities = ['*']
    ): string {
        return $user->createToken($name, $abilities)->plainTextToken;
    }

    /**
     * Revoke all tokens for the authenticated user.
     * លុប tokens ទាំងអស់សម្រាប់ user ដែលបាន authenticate។
     */
    public static function revokeAllTokens(Authenticatable $user): int
    {
        return $user->tokens()->delete();
    }

    /**
     * Revoke a specific token.
     * លុប token ជាក់លាក់។
     */
    public static function revokeToken(string $token): bool
    {
        $tokenModel = PersonalAccessToken::findToken($token);

        if ($tokenModel === null) {
            return false;
        }

        return $tokenModel->delete();
    }

    /**
     * Revoke tokens by name for the authenticated user.
     * លុប tokens តាមឈ្មោះសម្រាប់ user ដែលបាន authenticate។
     */
    public static function revokeTokensByName(Authenticatable $user, string $name): int
    {
        return $user->tokens()->where('name', $name)->delete();
    }

    /**
     * Get all tokens for the authenticated user.
     * ទទួល tokens ទាំងអស់សម្រាប់ user ដែលបាន authenticate។
     */
    public static function getUserTokens(Authenticatable $user): \Illuminate\Database\Eloquent\Collection
    {
        return $user->tokens;
    }

    /**
     * Get the current request's token.
     * ទទួល token នៃ request បច្ចុប្បន្ន។
     */
    public static function getCurrentToken(): ?PersonalAccessToken
    {
        $user = Auth::user();

        if ($user === null) {
            return null;
        }

        return $user->currentAccessToken();
    }

    /**
     * Check if a token is valid.
     * ពិនិត្យមើលថា token ត្រឹមត្រូវឬទេ។
     */
    public static function isValidToken(string $token): bool
    {
        $tokenModel = PersonalAccessToken::findToken($token);

        if ($tokenModel === null) {
            return false;
        }

        // Check if token is expired
        // ពិនិត្យមើលថា token ផុតកំណត់ឬទេ
        if ($tokenModel->expires_at !== null && $tokenModel->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Get token abilities.
     * ទទួល abilities នៃ token។
     */
    public static function getTokenAbilities(string $token): array
    {
        $tokenModel = PersonalAccessToken::findToken($token);

        if ($tokenModel === null) {
            return [];
        }

        return $tokenModel->abilities ?? [];
    }

    /**
     * Check if token has a specific ability.
     * ពិនិត្យមើលថា token មាន ability ជាក់លាក់ឬទេ។
     */
    public static function tokenCan(string $token, string $ability): bool
    {
        $abilities = self::getTokenAbilities($token);

        return in_array('*', $abilities, true) || in_array($ability, $abilities, true);
    }
}

