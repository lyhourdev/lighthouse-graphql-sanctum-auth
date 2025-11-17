<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\GraphQL\Mutations;

use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Spatie\Permission\Models\Permission;

/**
 * Remove Permission Mutation
 * 
 * Removes a permission from a user.
 * ដក permission ពី user។
 */
final class RemovePermission
{
    /**
     * Resolve the removePermission mutation.
     * ដោះស្រាយ removePermission mutation។
     * 
     * @param mixed $_
     * @param array<string, mixed> $args
     * @return User
     */
    public function __invoke(mixed $_, array $args, GraphQLContext $context)
    {
        $userModel = Auth::getProvider()->getModel();
        $user = $userModel::findOrFail($args['user_id']);
        $permission = Permission::findOrFail($args['permission_id']);

        $user->revokePermissionTo($permission);

        return $user->fresh();
    }
}

