<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\GraphQL\Directives;

use Illuminate\Support\Facades\Auth;
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\AuditLogger;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldMiddleware;

/**
 * Audit Directive
 * 
 * Logs field access and mutations for audit purposes.
 * កត់ត្រា field access និង mutations សម្រាប់ audit។
 * 
 * Usage in GraphQL schema:
 * type Mutation {
 *   deleteUser(id: ID!): User! @audit(action: "delete")
 * }
 */
final class AuditDirective extends BaseDirective implements FieldMiddleware
{
    public function __construct(
        private readonly AuditLogger $auditLogger,
    ) {}

    public static function definition(): string
    {
        return /** @lang GraphQL */ <<<'GRAPHQL'
"""
Log field access and mutations for audit purposes.
កត់ត្រា field access និង mutations សម្រាប់ audit។

This directive automatically logs all access to the field,
including the user, timestamp, and action performed.
Directive នេះកត់ត្រា access ទាំងអស់ទៅកាន់ field ដោយស្វ័យប្រវត្តិ,
រួមមាន user, timestamp, និង action ដែលបានអនុវត្ត។
"""
directive @audit(
    """
    The action being performed (e.g., "create", "update", "delete", "view").
    Action ដែលកំពុងអនុវត្ត (ឧ. "create", "update", "delete", "view")។
    """
    action: String = "access"
) on FIELD_DEFINITION
GRAPHQL;
    }

    public function handleField(FieldValue $fieldValue): void
    {
        // Check if audit logging is enabled
        // ពិនិត្យមើលថា audit logging ត្រូវបានបើក
        if (! config('lighthouse-sanctum-auth.audit.enabled', true)) {
            return;
        }

        $action = $this->directiveArgValue('action', 'access');
        $fieldName = $fieldValue->getFieldName();
        $parentType = $fieldValue->getParentName();

        $fieldValue->resultHandler(function ($result) use ($action, $fieldName, $parentType) {
            $user = Auth::user();

            $this->auditLogger->log([
                'user_id' => $user?->getKey(),
                'action' => $action,
                'field' => "{$parentType}.{$fieldName}",
                'ip_address' => request()?->ip(),
                'user_agent' => request()?->userAgent(),
                'timestamp' => now(),
            ]);

            return $result;
        });
    }
}

