<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\GraphQL\Mutations;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Revoke Permission From Role Mutation
 * 
 * Revokes a permission from a role.
 * ដក permission ពី role។
 */
final class RevokePermissionFromRole
{
    /**
     * Resolve the revokePermissionFromRole mutation.
     * ដោះស្រាយ revokePermissionFromRole mutation។
     * 
     * @param mixed $_
     * @param array<string, mixed> $args
     * @return Role
     */
    public function __invoke(mixed $_, array $args, GraphQLContext $context): Role
    {
        $role = Role::findOrFail($args['role_id']);
        $permission = Permission::findOrFail($args['permission_id']);

        $role->revokePermissionTo($permission);

        return $role->fresh();
    }
}

