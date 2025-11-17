<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\GraphQL\Mutations;

use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Spatie\Permission\Models\Role;

/**
 * Remove Role Mutation
 * 
 * Removes a role from a user.
 * ដក role ពី user។
 */
final class RemoveRole
{
    /**
     * Resolve the removeRole mutation.
     * ដោះស្រាយ removeRole mutation។
     * 
     * @param mixed $_
     * @param array<string, mixed> $args
     * @return User
     */
    public function __invoke(mixed $_, array $args, GraphQLContext $context)
    {
        $userModel = Auth::getProvider()->getModel();
        $user = $userModel::findOrFail($args['user_id']);
        $role = Role::findOrFail($args['role_id']);

        $user->removeRole($role);

        return $user->fresh();
    }
}

