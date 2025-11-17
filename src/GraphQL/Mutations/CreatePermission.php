<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\GraphQL\Mutations;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Spatie\Permission\Models\Permission;

/**
 * Create Permission Mutation
 * 
 * Creates a new permission.
 * បង្កើត permission ថ្មី។
 */
final class CreatePermission
{
    /**
     * Resolve the createPermission mutation.
     * ដោះស្រាយ createPermission mutation។
     * 
     * @param mixed $_
     * @param array<string, mixed> $args
     * @return Permission
     */
    public function __invoke(mixed $_, array $args, GraphQLContext $context): Permission
    {
        return Permission::create([
            'name' => $args['name'],
            'guard_name' => $args['guard_name'] ?? 'web',
        ]);
    }
}

