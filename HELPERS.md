# Helper Classes Documentation

Helper classes to simplify common operations when using this package.

## AuthHelper

Helper functions for authentication operations.

### Usage

```php
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers\AuthHelper;

// Get authenticated user
$user = AuthHelper::user();

// Check if user is authenticated
if (AuthHelper::check()) {
    // User is authenticated
}

// Get user ID
$userId = AuthHelper::id();

// Get user or throw exception
$user = AuthHelper::userOrFail();

// Check role
if (AuthHelper::hasRole('admin')) {
    // User has admin role
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

## PermissionHelper

Helper functions for role and permission operations.

### Usage

```php
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers\PermissionHelper;

// Find or create role
$role = PermissionHelper::findOrCreateRole('admin');

// Find or create permission
$permission = PermissionHelper::findOrCreatePermission('edit posts');

// Assign role to user
PermissionHelper::assignRoleToUser($user, 'admin');
// Or with Role object
PermissionHelper::assignRoleToUser($user, $role);

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

// Sync roles (removes all existing and assigns new ones)
PermissionHelper::syncRolesForUser($user, ['admin', 'moderator']);

// Sync permissions
PermissionHelper::syncPermissionsForUser($user, ['edit posts', 'delete posts']);

// Get user roles
$roles = PermissionHelper::getUserRoles();

// Get user permissions
$permissions = PermissionHelper::getUserPermissions();
```

## TenantHelper

Helper functions for tenant operations.

### Usage

```php
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers\TenantHelper;

// Get current tenant ID
$tenantId = TenantHelper::currentTenantId();

// Check if multi-tenancy is enabled
if (TenantHelper::isEnabled()) {
    // Multi-tenancy is enabled
}

// Get tenant ID from authenticated user
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

Helper functions for token operations.

### Usage

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

Helper functions for GraphQL operations with automatic error handling.

### Usage

```php
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers\GraphQLHelper;

// In your GraphQL resolvers:

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

## ResponseHelper

Helper functions for creating standardized GraphQL responses.

### Usage

```php
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers\ResponseHelper;

// Success response
return ResponseHelper::success(['id' => 1, 'name' => 'John'], 'User created successfully');

// Error response
return ResponseHelper::error('User not found', 'USER_NOT_FOUND', ['user_id' => 123]);

// Paginated response
return ResponseHelper::paginated($users, $total, $page, $perPage);
```

## Example: Using Helpers in a Resolver

```php
<?php

namespace App\GraphQL\Mutations;

use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers\{
    AuthHelper,
    GraphQLHelper,
    PermissionHelper,
    ResponseHelper
};

final class CreatePost
{
    public function __invoke(mixed $_, array $args, $context)
    {
        // Require authentication
        GraphQLHelper::requireAuth();
        
        // Require specific permission
        GraphQLHelper::requirePermission('create posts');
        
        // Get authenticated user
        $user = AuthHelper::userOrFail();
        
        // Create post logic here...
        $post = Post::create([
            'title' => $args['title'],
            'user_id' => $user->id,
        ]);
        
        // Return success response
        return ResponseHelper::success(['post' => $post], 'Post created successfully');
    }
}
```

## Benefits

- **Cleaner Code**: Reduce boilerplate code in resolvers
- **Consistent Error Handling**: Standardized error messages
- **Type Safety**: All helpers use strict types
- **Easy to Use**: Simple static method calls
- **Bilingual Support**: Comments in both English and Khmer

