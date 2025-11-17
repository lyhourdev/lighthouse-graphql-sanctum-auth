<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\GraphQL\Mutations;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Give Permission To Role Mutation
 * 
 * Gives a permission to a role.
 * ផ្តល់ permission ទៅ role។
 */
final class GivePermissionToRole
{
    /**
     * Resolve the givePermissionToRole mutation.
     * ដោះស្រាយ givePermissionToRole mutation។
     * 
     * @param mixed $_
     * @param array<string, mixed> $args
     * @return Role
     */
    public function __invoke(mixed $_, array $args, GraphQLContext $context): Role
    {
        $role = Role::findOrFail($args['role_id']);
        $permission = Permission::findOrFail($args['permission_id']);

        $role->givePermissionTo($permission);

        return $role->fresh();
    }
}

