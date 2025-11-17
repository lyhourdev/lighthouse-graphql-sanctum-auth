<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\GraphQL\Directives;

use GraphQL\Error\Error;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Tenancy\TenantResolver;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldMiddleware;

/**
 * BelongsToTenant Directive
 * 
 * Ensures that resources belong to the current tenant.
 * ធានាថា resources ជាកម្មសិទ្ធិរបស់ tenant បច្ចុប្បន្ន។
 * 
 * Usage in GraphQL schema:
 * type Query {
 *   posts: [Post!]! @belongsToTenant
 * }
 */
final class BelongsToTenantDirective extends BaseDirective implements FieldMiddleware
{
    public function __construct(
        private readonly TenantResolver $tenantResolver,
    ) {}

    public static function definition(): string
    {
        return /** @lang GraphQL */ <<<'GRAPHQL'
"""
Ensure the resource belongs to the current tenant.
ធានាថា resource ជាកម្មសិទ្ធិរបស់ tenant បច្ចុប្បន្ន។

This directive automatically filters resources by tenant ID
and ensures users can only access resources from their tenant.
Directive នេះច្រោះ resources ដោយ tenant ID ដោយស្វ័យប្រវត្តិ
និងធានាថា users អាចចូលប្រើប្រាស់ resources ពី tenant របស់ពួកគេប៉ុណ្ណោះ។
"""
directive @belongsToTenant(
    """
    The relation field name that contains the tenant ID (default: "tenant_id").
    ឈ្មោះ relation field ដែលមាន tenant ID (លំនាំដើម: "tenant_id")។
    """
    relation: String = "tenant_id"
) on FIELD_DEFINITION
GRAPHQL;
    }

    public function handleField(FieldValue $fieldValue): void
    {
        $relationField = $this->directiveArgValue('relation', 'tenant_id');

        // Check if tenancy is enabled
        // ពិនិត្យមើលថា tenancy ត្រូវបានបើក
        if (! config('lighthouse-sanctum-auth.tenancy.enabled', false)) {
            return;
        }

        $fieldValue->wrapResolver(function (callable $resolver) use ($relationField): \Closure {
            return function (mixed $root, array $args, $context, $resolveInfo) use ($resolver, $relationField): mixed {
                $user = Auth::user();

                if ($user === null) {
                    throw new AuthenticationException('You must be authenticated to access this field.');
                }

                $tenantId = $this->tenantResolver->resolve($context->request());

                if ($tenantId === null) {
                    throw new Error('Tenant could not be resolved. Please ensure tenant identification is configured.');
                }

                $result = $resolver($root, $args, $context, $resolveInfo);

                // If result is a collection, filter by tenant
                // ប្រសិនបើ result គឺ collection, ច្រោះតាម tenant
                if (is_iterable($result) && ! $result instanceof Model) {
                    $filtered = [];
                    foreach ($result as $item) {
                        if ($item instanceof Model) {
                            $itemTenantId = $item->getAttribute($relationField);
                            if ((string) $itemTenantId === (string) $tenantId) {
                                $filtered[] = $item;
                            }
                        }
                    }

                    return $filtered;
                }

                // If result is a single model, check tenant
                // ប្រសិនបើ result គឺ model តែមួយ, ពិនិត្យមើល tenant
                if ($result instanceof Model) {
                    $itemTenantId = $result->getAttribute($relationField);
                    if ((string) $itemTenantId !== (string) $tenantId) {
                        throw new Error('Unauthorized: This resource does not belong to your tenant.');
                    }
                }

                return $result;
            };
        });
    }
}

