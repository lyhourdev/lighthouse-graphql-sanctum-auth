<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\GraphQL\Directives;

use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldMiddleware;

/**
 * HasRole Directive
 * 
 * Checks if the authenticated user has the specified role.
 * ពិនិត្យមើលថា user ដែលបាន authenticate មាន role ដែលបានបញ្ជាក់ឬទេ។
 * 
 * Usage in GraphQL schema:
 * type Query {
 *   users: [User!]! @hasRole(role: "admin")
 * }
 */
final class HasRoleDirective extends BaseDirective implements FieldMiddleware
{
    public static function definition(): string
    {
        return /** @lang GraphQL */ <<<'GRAPHQL'
"""
Ensure the authenticated user has a specific role.
ធានាថា user ដែលបាន authenticate មាន role ជាក់លាក់។

You may use this directive on fields to restrict access to users with specific roles.
អ្នកអាចប្រើ directive នេះលើ fields ដើម្បីកំណត់ការចូលប្រើប្រាស់ទៅកាន់ users ដែលមាន roles ជាក់លាក់។
"""
directive @hasRole(
    """
    The role name that is required.
    ឈ្មោះ role ដែលត្រូវការ។
    """
    role: String!
) on FIELD_DEFINITION
GRAPHQL;
    }

    public function handleField(FieldValue $fieldValue): void
    {
        $requiredRole = $this->directiveArgValue('role');

        $fieldValue->wrapResolver(function (callable $resolver) use ($requiredRole): \Closure {
            return function (mixed $root, array $args, $context, $resolveInfo) use ($resolver, $requiredRole): mixed {
                $user = Auth::user();

                if ($user === null) {
                    throw new AuthenticationException('You must be authenticated to access this field.');
                }

                if (! method_exists($user, 'hasRole')) {
                    throw new Error('User model must use Spatie Permission trait.');
                }

                if (! $user->hasRole($requiredRole)) {
                    throw new Error("Unauthorized: You must have the '{$requiredRole}' role to access this field.");
                }

                return $resolver($root, $args, $context, $resolveInfo);
            };
        });
    }
}

