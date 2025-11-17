<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\GraphQL\Mutations;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Spatie\Permission\Models\Role;

/**
 * Delete Role Mutation
 * 
 * Deletes a role.
 * លុប role។
 */
final class DeleteRole
{
    /**
     * Resolve the deleteRole mutation.
     * ដោះស្រាយ deleteRole mutation។
     * 
     * @param mixed $_
     * @param array<string, mixed> $args
     * @return bool
     */
    public function __invoke(mixed $_, array $args, GraphQLContext $context): bool
    {
        $role = Role::findOrFail($args['id']);

        return $role->delete();
    }
}

