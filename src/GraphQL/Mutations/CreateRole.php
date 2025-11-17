<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\GraphQL\Mutations;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Spatie\Permission\Models\Role;

/**
 * Create Role Mutation
 * 
 * Creates a new role.
 * បង្កើត role ថ្មី។
 */
final class CreateRole
{
    /**
     * Resolve the createRole mutation.
     * ដោះស្រាយ createRole mutation។
     * 
     * @param mixed $_
     * @param array<string, mixed> $args
     * @return Role
     */
    public function __invoke(mixed $_, array $args, GraphQLContext $context): Role
    {
        return Role::create([
            'name' => $args['name'],
            'guard_name' => $args['guard_name'] ?? 'web',
        ]);
    }
}

