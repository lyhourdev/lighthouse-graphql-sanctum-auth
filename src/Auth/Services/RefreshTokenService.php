<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\Auth\Services;

use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * Refresh Token Service
 * 
 * Handles refresh token operations.
 * ដោះស្រាយ operations នៃ refresh token។
 */
final class RefreshTokenService
{
    /**
     * Refresh the access token.
     * ធ្វើឱ្យ access token ថ្មី។
     * 
     * @return array<string, mixed>
     */
    public function refresh(string $refreshToken): array
    {
        $token = PersonalAccessToken::findToken($refreshToken);

        if ($token === null) {
            throw new \RuntimeException('Invalid refresh token.');
        }

        $user = $token->tokenable;

        // Revoke old token
        // លុប token ចាស់
        $token->delete();

        // Create new token
        // បង្កើត token ថ្មី
        $newToken = $user->createToken('refresh-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $newToken,
            'token_type' => 'Bearer',
        ];
    }
}

