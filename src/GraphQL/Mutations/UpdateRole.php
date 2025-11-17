<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\GraphQL\Mutations;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Spatie\Permission\Models\Role;

/**
 * Update Role Mutation
 * 
 * Updates an existing role.
 * ធ្វើបច្ចុប្បន្នភាព role។
 */
final class UpdateRole
{
    /**
     * Resolve the updateRole mutation.
     * ដោះស្រាយ updateRole mutation។
     * 
     * @param mixed $_
     * @param array<string, mixed> $args
     * @return Role
     */
    public function __invoke(mixed $_, array $args, GraphQLContext $context): Role
    {
        $role = Role::findOrFail($args['id']);

        $role->update(array_filter([
            'name' => $args['name'] ?? null,
            'guard_name' => $args['guard_name'] ?? null,
        ]));

        return $role->fresh();
    }
}

