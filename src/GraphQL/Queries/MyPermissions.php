<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\GraphQL\Queries;

use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * My Permissions Query
 * 
 * Returns all permissions for the authenticated user.
 * ត្រឡប់ permissions ទាំងអស់សម្រាប់ user ដែលបាន authenticate។
 */
final class MyPermissions
{
    /**
     * Resolve the myPermissions query.
     * ដោះស្រាយ myPermissions query។
     * 
     * @param mixed $_
     * @param array<string, mixed> $args
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function __invoke(mixed $_, array $args, GraphQLContext $context): \Illuminate\Database\Eloquent\Collection
    {
        $user = Auth::user();

        if ($user === null) {
            return collect();
        }

        return $user->getAllPermissions();
    }
}

