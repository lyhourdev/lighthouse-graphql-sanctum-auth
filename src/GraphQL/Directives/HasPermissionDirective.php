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
 * HasPermission Directive
 * 
 * Checks if the authenticated user has the specified permission.
 * ពិនិត្យមើលថា user ដែលបាន authenticate មាន permission ដែលបានបញ្ជាក់ឬទេ។
 * 
 * Usage in GraphQL schema:
 * type Mutation {
 *   deleteUser(id: ID!): User! @hasPermission(permission: "delete users")
 * }
 */
final class HasPermissionDirective extends BaseDirective implements FieldMiddleware
{
    public static function definition(): string
    {
        return /** @lang GraphQL */ <<<'GRAPHQL'
"""
Ensure the authenticated user has a specific permission.
ធានាថា user ដែលបាន authenticate មាន permission ជាក់លាក់។

You may use this directive on fields to restrict access to users with specific permissions.
អ្នកអាចប្រើ directive នេះលើ fields ដើម្បីកំណត់ការចូលប្រើប្រាស់ទៅកាន់ users ដែលមាន permissions ជាក់លាក់។
"""
directive @hasPermission(
    """
    The permission name that is required.
    ឈ្មោះ permission ដែលត្រូវការ។
    """
    permission: String!
) on FIELD_DEFINITION
GRAPHQL;
    }

    public function handleField(FieldValue $fieldValue): void
    {
        $requiredPermission = $this->directiveArgValue('permission');

        $fieldValue->wrapResolver(function (callable $resolver) use ($requiredPermission): \Closure {
            return function (mixed $root, array $args, $context, $resolveInfo) use ($resolver, $requiredPermission): mixed {
                $user = Auth::user();

                if ($user === null) {
                    throw new AuthenticationException('You must be authenticated to access this field.');
                }

                if (! method_exists($user, 'can')) {
                    throw new Error('User model must use Spatie Permission trait.');
                }

                if (! $user->can($requiredPermission)) {
                    throw new Error("Unauthorized: You must have the '{$requiredPermission}' permission to access this field.");
                }

                return $resolver($root, $args, $context, $resolveInfo);
            };
        });
    }
}

