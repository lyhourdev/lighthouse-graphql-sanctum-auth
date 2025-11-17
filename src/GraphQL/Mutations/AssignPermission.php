<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\GraphQL\Mutations;

use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Spatie\Permission\Models\Permission;

/**
 * Assign Permission Mutation
 * 
 * Assigns a permission to a user.
 * ផ្តល់ permission ទៅ user។
 */
final class AssignPermission
{
    /**
     * Resolve the assignPermission mutation.
     * ដោះស្រាយ assignPermission mutation។
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

        $user->givePermissionTo($permission);

        return $user->fresh();
    }
}

