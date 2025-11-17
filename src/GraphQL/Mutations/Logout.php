<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\GraphQL\Mutations;

use Illuminate\Support\Facades\Auth;

/**
 * Logout Mutation
 * 
 * Logs out the current user and revokes all tokens.
 * Logout user បច្ចុប្បន្ន និងលុប tokens ទាំងអស់។
 */
final class Logout
{
    /**
     * Resolve the logout mutation.
     * ដោះស្រាយ logout mutation។
     * 
     * @param mixed $_
     * @param array<string, mixed> $args
     * @return bool
     */
    public function __invoke(mixed $_, array $args): bool
    {
        $user = Auth::user();

        if ($user !== null) {
            // Revoke all tokens for the user
            // លុប tokens ទាំងអស់សម្រាប់ user
            $user->tokens()->delete();
        }

        return true;
    }
}

