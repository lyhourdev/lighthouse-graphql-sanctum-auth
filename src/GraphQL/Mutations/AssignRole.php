<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\GraphQL\Mutations;

use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Spatie\Permission\Models\Role;

/**
 * Assign Role Mutation
 * 
 * Assigns a role to a user.
 * ផ្តល់ role ទៅ user។
 */
final class AssignRole
{
    /**
     * Resolve the assignRole mutation.
     * ដោះស្រាយ assignRole mutation។
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

        $user->assignRole($role);

        return $user->fresh();
    }
}

