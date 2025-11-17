<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\GraphQL\Queries;

use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * Me Query
 * 
 * Returns the currently authenticated user.
 * ត្រឡប់ user ដែលបាន authenticate បច្ចុប្បន្ន។
 */
final class Me
{
    /**
     * Resolve the me query.
     * ដោះស្រាយ me query។
     * 
     * @param mixed $_
     * @param array<string, mixed> $args
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function __invoke(mixed $_, array $args, GraphQLContext $context): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        return Auth::user();
    }
}

