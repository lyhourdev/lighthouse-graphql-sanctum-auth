<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\Auth\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Login Service
 * 
 * Handles user authentication and token generation.
 * ដោះស្រាយ authentication និងការបង្កើត token របស់ user។
 */
final class LoginService
{
    /**
     * Authenticate user and generate tokens.
     * Authenticate user និងបង្កើត tokens។
     * 
     * @param array<string, mixed> $credentials
     * @return array<string, mixed>
     * @throws ValidationException
     */
    public function login(array $credentials, string $deviceName, ?string $ipAddress = null, ?string $userAgent = null): array
    {
        $user = $this->authenticate($credentials);

        // Create token with device name and abilities
        // បង្កើត token ជាមួយ device name និង abilities
        $token = $user->createToken($deviceName, ['*'])->plainTextToken;

        // Note: IP and user agent can be stored in a separate device management table if needed
        // ចំណាំ: IP និង user agent អាចត្រូវបានរក្សាទុកក្នុងតារាង device management ដាច់ដោយឡែក ប្រសិនបើត្រូវការ

        return [
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ];
    }

    /**
     * Authenticate user with credentials.
     * Authenticate user ជាមួយ credentials។
     * 
     * @param array<string, mixed> $credentials
     * @throws ValidationException
     */
    private function authenticate(array $credentials): Authenticatable
    {
        $user = Auth::getProvider()->retrieveByCredentials($credentials);

        if ($user === null || ! Hash::check($credentials['password'], $user->getAuthPassword())) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $user;
    }
}

