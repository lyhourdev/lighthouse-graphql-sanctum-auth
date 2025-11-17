<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\GraphQL\Directives;

use GraphQL\Error\Error;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldMiddleware;

/**
 * Ownership Directive
 * 
 * Ensures that the user can only access resources they own.
 * ធានាថា user អាចចូលប្រើប្រាស់ resources ដែលពួកគេជាម្ចាស់ប៉ុណ្ណោះ។
 * 
 * Usage in GraphQL schema:
 * type Query {
 *   myPost(id: ID!): Post @ownership(relation: "user_id")
 * }
 */
final class OwnershipDirective extends BaseDirective implements FieldMiddleware
{
    public static function definition(): string
    {
        return /** @lang GraphQL */ <<<'GRAPHQL'
"""
Ensure the authenticated user owns the resource.
ធានាថា user ដែលបាន authenticate ជាម្ចាស់ resource។

This directive checks if the resource belongs to the authenticated user
by comparing the specified relation field with the user's ID.
Directive នេះពិនិត្យមើលថា resource ជាកម្មសិទ្ធិរបស់ user ដែលបាន authenticate
ដោយប្រៀបធៀប relation field ដែលបានបញ្ជាក់ជាមួយ ID របស់ user។
"""
directive @ownership(
    """
    The relation field name that contains the user ID (default: "user_id").
    ឈ្មោះ relation field ដែលមាន user ID (លំនាំដើម: "user_id")។
    """
    relation: String = "user_id"
) on FIELD_DEFINITION
GRAPHQL;
    }

    public function handleField(FieldValue $fieldValue): void
    {
        $relationField = $this->directiveArgValue('relation', 'user_id');

        $fieldValue->wrapResolver(function (callable $resolver) use ($relationField): \Closure {
            return function (mixed $root, array $args, $context, $resolveInfo) use ($resolver, $relationField): mixed {
                $user = Auth::user();

                if ($user === null) {
                    throw new AuthenticationException('You must be authenticated to access this field.');
                }

                $result = $resolver($root, $args, $context, $resolveInfo);

                // If result is null, allow it (not found)
                // ប្រសិនបើ result គឺ null, អនុញ្ញាត (មិនឃើញ)
                if ($result === null) {
                    return null;
                }

                // If result is a collection, check each item
                // ប្រសិនបើ result គឺ collection, ពិនិត្យមើលធាតុនីមួយៗ
                if (is_iterable($result) && ! $result instanceof Model) {
                    foreach ($result as $item) {
                        if ($item instanceof Model) {
                            $this->checkOwnership($item, $user->getKey(), $relationField);
                        }
                    }

                    return $result;
                }

                // If result is a single model, check ownership
                // ប្រសិនបើ result គឺ model តែមួយ, ពិនិត្យមើល ownership
                if ($result instanceof Model) {
                    $this->checkOwnership($result, $user->getKey(), $relationField);
                }

                return $result;
            };
        });
    }

    /**
     * Check if the model belongs to the user.
     * ពិនិត្យមើលថា model ជាកម្មសិទ្ធិរបស់ user។
     */
    private function checkOwnership(Model $model, string|int $userId, string $relationField): void
    {
        $ownerId = $model->getAttribute($relationField);

        if ($ownerId === null) {
            throw new Error("Resource does not have an owner field '{$relationField}'.");
        }

        if ((string) $ownerId !== (string) $userId) {
            throw new Error('Unauthorized: You do not own this resource.');
        }
    }
}

