<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\GraphQL\Mutations;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Spatie\Permission\Models\Permission;

/**
 * Update Permission Mutation
 * 
 * Updates an existing permission.
 * ធ្វើបច្ចុប្បន្នភាព permission។
 */
final class UpdatePermission
{
    /**
     * Resolve the updatePermission mutation.
     * ដោះស្រាយ updatePermission mutation។
     * 
     * @param mixed $_
     * @param array<string, mixed> $args
     * @return Permission
     */
    public function __invoke(mixed $_, array $args, GraphQLContext $context): Permission
    {
        $permission = Permission::findOrFail($args['id']);

        $permission->update(array_filter([
            'name' => $args['name'] ?? null,
            'guard_name' => $args['guard_name'] ?? null,
        ]));

        return $permission->fresh();
    }
}

