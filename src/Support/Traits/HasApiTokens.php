<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Traits;

use Laravel\Sanctum\HasApiTokens as SanctumHasApiTokens;

/**
 * Has Api Tokens Trait
 * 
 * Extends Sanctum's HasApiTokens trait with additional helper methods.
 * ពង្រីក Sanctum's HasApiTokens trait ជាមួយ helper methods បន្ថែម។
 */
trait HasApiTokens
{
    use SanctumHasApiTokens;

    /**
     * Create a token with device information.
     * បង្កើត token ជាមួយ device information។
     * 
     * @param array<int, string> $abilities
     * @return \Laravel\Sanctum\NewAccessToken
     */
    public function createTokenWithDevice(
        string $name,
        array $abilities = ['*'],
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): \Laravel\Sanctum\NewAccessToken {
        $token = $this->createToken($name, $abilities);

        // Store device information if needed
        // រក្សាទុក device information ប្រសិនបើត្រូវការ
        if ($ipAddress !== null || $userAgent !== null) {
            // You can extend this to store in a devices table
            // អ្នកអាចពង្រីកនេះដើម្បីរក្សាទុកក្នុងតារាង devices
        }

        return $token;
    }

    /**
     * Revoke all tokens except the current one.
     * លុប tokens ទាំងអស់លើកលែងតែ current token។
     */
    public function revokeOtherTokens(): int
    {
        $currentToken = $this->currentAccessToken();

        if ($currentToken === null) {
            return $this->tokens()->delete();
        }

        return $this->tokens()
            ->where('id', '!=', $currentToken->id)
            ->delete();
    }

    /**
     * Check if user has a valid token.
     * ពិនិត្យមើលថា user មាន token ត្រឹមត្រូវឬទេ។
     */
    public function hasValidToken(): bool
    {
        return $this->tokens()
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }
}

