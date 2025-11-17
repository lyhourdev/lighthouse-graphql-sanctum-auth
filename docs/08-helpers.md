# Helpers Guide / មគ្គុទេសក៍ Helpers

Complete reference for all helper classes.
ឯកសារពេញលេញសម្រាប់ helper classes ទាំងអស់។

## AuthHelper / AuthHelper

Authentication helper functions.
មុខងារជំនួយ authentication។

### Methods / Methods

```php
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers\AuthHelper;

// Get authenticated user
// ទទួល user ដែលបាន authenticate
$user = AuthHelper::user();

// Check if authenticated
// ពិនិត្យមើលថាបាន authenticate
if (AuthHelper::check()) {
    // User is authenticated
    // User បាន authenticate
}

// Get user ID
// ទទួល user ID
$userId = AuthHelper::id();

// Get user or throw exception
// ទទួល user ឬ throw exception
$user = AuthHelper::userOrFail();

// Check role
// ពិនិត្យមើល role
if (AuthHelper::hasRole('admin')) {
    // User has admin role
    // User មាន admin role
}

// Check any role
if (AuthHelper::hasAnyRole(['admin', 'moderator'])) {
    // User has at least one role
}

// Check all roles
if (AuthHelper::hasAllRoles(['admin', 'super-admin'])) {
    // User has all roles
}

// Check permission
if (AuthHelper::can('edit posts')) {
    // User has permission
}

// Check any permission
if (AuthHelper::canAny(['edit posts', 'delete posts'])) {
    // User has at least one permission
}

// Check all permissions
if (AuthHelper::canAll(['edit posts', 'delete posts'])) {
    // User has all permissions
}
```

## PermissionHelper / PermissionHelper

Role and permission management helper.
Helper ការគ្រប់គ្រង roles និង permissions។

### Methods / Methods

```php
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers\PermissionHelper;

// Find or create role
// រក ឬបង្កើត role
$role = PermissionHelper::findOrCreateRole('admin');

// Find or create permission
// រក ឬបង្កើត permission
$permission = PermissionHelper::findOrCreatePermission('edit posts');

// Assign role to user
PermissionHelper::assignRoleToUser($user, 'admin');

// Remove role from user
PermissionHelper::removeRoleFromUser($user, 'admin');

// Give permission to user
PermissionHelper::givePermissionToUser($user, 'edit posts');

// Revoke permission from user
PermissionHelper::revokePermissionFromUser($user, 'edit posts');

// Give permission to role
PermissionHelper::givePermissionToRole('admin', 'edit posts');

// Revoke permission from role
PermissionHelper::revokePermissionFromRole('admin', 'edit posts');

// Sync roles
PermissionHelper::syncRolesForUser($user, ['admin', 'editor']);

// Sync permissions
PermissionHelper::syncPermissionsForUser($user, ['edit posts', 'delete posts']);

// Get user roles
$roles = PermissionHelper::getUserRoles();

// Get user permissions
$permissions = PermissionHelper::getUserPermissions();
```

## TenantHelper

Multi-tenancy helper functions.

### Methods

```php
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers\TenantHelper;

// Get current tenant ID
$tenantId = TenantHelper::currentTenantId();

// Check if multi-tenancy is enabled
if (TenantHelper::isEnabled()) {
    // Multi-tenancy is enabled
}

// Get tenant ID from user
$tenantId = TenantHelper::getTenantIdFromUser();

// Set tenant context
TenantHelper::setTenantContext('tenant-123');

// Get tenant context
$tenantId = TenantHelper::getTenantContext();

// Check if user belongs to tenant
if (TenantHelper::userBelongsToTenant('tenant-123')) {
    // User belongs to tenant
}

// Ensure user belongs to tenant (throws exception if not)
TenantHelper::ensureUserBelongsToTenant('tenant-123');
```

## TokenHelper

Token management helper functions.

### Methods

```php
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers\TokenHelper;

// Create token
$token = TokenHelper::createToken($user, 'mobile-app', ['read', 'write']);

// Revoke all tokens
$deleted = TokenHelper::revokeAllTokens($user);

// Revoke specific token
TokenHelper::revokeToken($tokenString);

// Revoke tokens by name
TokenHelper::revokeTokensByName($user, 'mobile-app');

// Get all user tokens
$tokens = TokenHelper::getUserTokens($user);

// Get current request token
$token = TokenHelper::getCurrentToken();

// Check if token is valid
if (TokenHelper::isValidToken($tokenString)) {
    // Token is valid
}

// Get token abilities
$abilities = TokenHelper::getTokenAbilities($tokenString);

// Check if token has ability
if (TokenHelper::tokenCan($tokenString, 'read')) {
    // Token has read ability
}
```

## GraphQLHelper

GraphQL operations with automatic error handling.

### Methods

```php
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers\GraphQLHelper;

// Require authentication
GraphQLHelper::requireAuth();

// Require specific role
GraphQLHelper::requireRole('admin');

// Require specific permission
GraphQLHelper::requirePermission('edit posts');

// Require any role
GraphQLHelper::requireAnyRole(['admin', 'moderator']);

// Require any permission
GraphQLHelper::requireAnyPermission(['edit posts', 'delete posts']);

// Require ownership
GraphQLHelper::requireOwnership($post, 'user_id');
```

### Example Usage

```php
<?php

namespace App\GraphQL\Mutations;

use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers\GraphQLHelper;

final class DeletePost
{
    public function __invoke(mixed $_, array $args, $context)
    {
        // Require authentication
        GraphQLHelper::requireAuth();
        
        // Require permission
        GraphQLHelper::requirePermission('delete posts');
        
        $post = Post::findOrFail($args['id']);
        
        // Require ownership
        GraphQLHelper::requireOwnership($post);
        
        $post->delete();
        
        return true;
    }
}
```

## ResponseHelper

Standardized GraphQL responses.

### Methods

```php
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers\ResponseHelper;

// Success response
return ResponseHelper::success(
    ['id' => 1, 'name' => 'John'],
    'User created successfully'
);

// Error response
return ResponseHelper::error(
    'User not found',
    'USER_NOT_FOUND',
    ['user_id' => 123]
);

// Paginated response
return ResponseHelper::paginated(
    $users,
    $total,
    $page,
    $perPage
);
```

### Example Usage

```php
<?php

namespace App\GraphQL\Mutations;

use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers\{
    GraphQLHelper,
    ResponseHelper
};

final class CreatePost
{
    public function __invoke(mixed $_, array $args, $context)
    {
        GraphQLHelper::requireAuth();
        GraphQLHelper::requirePermission('create posts');
        
        $post = Post::create($args['input']);
        
        return ResponseHelper::success(
            ['post' => $post],
            'Post created successfully'
        );
    }
}
```

## Complete Example

```php
<?php

namespace App\GraphQL\Mutations;

use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers\{
    AuthHelper,
    GraphQLHelper,
    PermissionHelper,
    ResponseHelper,
    TenantHelper
};

final class CreatePost
{
    public function __invoke(mixed $_, array $args, $context)
    {
        // Require authentication
        GraphQLHelper::requireAuth();
        
        // Require permission
        GraphQLHelper::requirePermission('create posts');
        
        // Get authenticated user
        $user = AuthHelper::userOrFail();
        
        // Get tenant ID if multi-tenancy is enabled
        $tenantId = TenantHelper::currentTenantId();
        
        // Create post
        $post = Post::create([
            'title' => $args['input']['title'],
            'content' => $args['input']['content'],
            'user_id' => $user->id,
            'tenant_id' => $tenantId,
        ]);
        
        // Return success response
        return ResponseHelper::success(
            ['post' => $post],
            'Post created successfully'
        );
    }
}
```

## Next Steps

- Read [Traits & Models Guide](./09-traits-models.md)
- Read [Examples](./11-examples.md)

