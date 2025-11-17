<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\GraphQL\Mutations;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Spatie\Permission\Models\Permission;

/**
 * Delete Permission Mutation
 * 
 * Deletes a permission.
 * លុប permission។
 */
final class DeletePermission
{
    /**
     * Resolve the deletePermission mutation.
     * ដោះស្រាយ deletePermission mutation។
     * 
     * @param mixed $_
     * @param array<string, mixed> $args
     * @return bool
     */
    public function __invoke(mixed $_, array $args, GraphQLContext $context): bool
    {
        $permission = Permission::findOrFail($args['id']);

        return $permission->delete();
    }
}

