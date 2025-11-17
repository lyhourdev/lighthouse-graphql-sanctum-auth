# Permissions & Roles Guide / មគ្គុទេសក៍ Permissions & Roles

Complete guide to using Spatie Laravel Permission with GraphQL.
មគ្គុទេសក៍ពេញលេញសម្រាប់ការប្រើ Spatie Laravel Permission ជាមួយ GraphQL។

## Overview / ទិដ្ឋភាពទូទៅ

This package integrates Spatie Laravel Permission to provide role and permission management through GraphQL.
Package នេះរួមបញ្ចូល Spatie Laravel Permission ដើម្បីផ្តល់ការគ្រប់គ្រង roles និង permissions តាម GraphQL។

## GraphQL Schema / GraphQL Schema

The package provides comprehensive permissions and roles operations:
Package ផ្តល់ operations permissions និង roles ពេញលេញ:

### Types / Types

```graphql
type Role {
  id: ID!
  name: String!
  guard_name: String!
  permissions: [Permission!]!
  users: [User!]!
}

type Permission {
  id: ID!
  name: String!
  guard_name: String!
  roles: [Role!]!
  users: [User!]!
}

extend type User {
  roles: [Role!]!
  permissions: [Permission!]!
}
```

## Queries / Queries

### Get All Roles / ទទួល Roles ទាំងអស់

```graphql
query {
  roles {
    id
    name
    permissions {
      name
    }
  }
}
```

**Required Permission:** `view roles` / **Permission ត្រូវការ:** `view roles`

### Get Role by ID / ទទួល Role តាម ID

```graphql
query {
  role(id: 1) {
    id
    name
    permissions {
      name
    }
  }
}
```

**Required Permission:** `view roles` / **Permission ត្រូវការ:** `view roles`

### Get All Permissions / ទទួល Permissions ទាំងអស់

```graphql
query {
  permissions {
    id
    name
    roles {
      name
    }
  }
}
```

**Required Permission:** `view permissions` / **Permission ត្រូវការ:** `view permissions`

### Get My Roles / ទទួល Roles របស់ខ្ញុំ

```graphql
query {
  myRoles {
    id
    name
  }
}
```

### Get My Permissions / ទទួល Permissions របស់ខ្ញុំ

```graphql
query {
  myPermissions {
    id
    name
  }
}
```

## Mutations / Mutations

### Assign Role to User / ផ្តល់ Role ទៅ User

```graphql
mutation {
  assignRole(user_id: 1, role_id: 1) {
    id
    name
    roles {
      name
    }
  }
}
```

**Required Permission:** `assign roles` / **Permission ត្រូវការ:** `assign roles`

### Remove Role from User / ដក Role ពី User

```graphql
mutation {
  removeRole(user_id: 1, role_id: 1) {
    id
    name
  }
}
```

**Required Permission:** `remove roles` / **Permission ត្រូវការ:** `remove roles`

### Assign Permission to User / ផ្តល់ Permission ទៅ User

```graphql
mutation {
  assignPermission(user_id: 1, permission_id: 1) {
    id
    name
    permissions {
      name
    }
  }
}
```

**Required Permission:** `assign permissions` / **Permission ត្រូវការ:** `assign permissions`

### Remove Permission from User / ដក Permission ពី User

```graphql
mutation {
  removePermission(user_id: 1, permission_id: 1) {
    id
    name
  }
}
```

**Required Permission:** `remove permissions` / **Permission ត្រូវការ:** `remove permissions`

### Give Permission to Role / ផ្តល់ Permission ទៅ Role

```graphql
mutation {
  givePermissionToRole(role_id: 1, permission_id: 1) {
    id
    name
    permissions {
      name
    }
  }
}
```

**Required Permission:** `manage roles` / **Permission ត្រូវការ:** `manage roles`

### Revoke Permission from Role / ដក Permission ពី Role

```graphql
mutation {
  revokePermissionFromRole(role_id: 1, permission_id: 1) {
    id
    name
  }
}
```

**Required Permission:** `manage roles` / **Permission ត្រូវការ:** `manage roles`

### Create Role / បង្កើត Role

```graphql
mutation {
  createRole(name: "editor", guard_name: "web") {
    id
    name
  }
}
```

**Required Permission:** `create roles` / **Permission ត្រូវការ:** `create roles`

### Update Role / ធ្វើបច្ចុប្បន្នភាព Role

```graphql
mutation {
  updateRole(id: 1, name: "senior-editor") {
    id
    name
  }
}
```

**Required Permission:** `update roles` / **Permission ត្រូវការ:** `update roles`

### Delete Role / លុប Role

```graphql
mutation {
  deleteRole(id: 1)
}
```

**Required Permission:** `delete roles` / **Permission ត្រូវការ:** `delete roles`

### Create Permission / បង្កើត Permission

```graphql
mutation {
  createPermission(name: "publish posts", guard_name: "web") {
    id
    name
  }
}
```

**Required Permission:** `create permissions` / **Permission ត្រូវការ:** `create permissions`

### Update Permission / ធ្វើបច្ចុប្បន្នភាព Permission

```graphql
mutation {
  updatePermission(id: 1, name: "publish articles") {
    id
    name
  }
}
```

**Required Permission:** `update permissions` / **Permission ត្រូវការ:** `update permissions`

### Delete Permission / លុប Permission

```graphql
mutation {
  deletePermission(id: 1)
}
```

**Required Permission:** `delete permissions` / **Permission ត្រូវការ:** `delete permissions`

## Using Helpers / ការប្រើ Helpers

### PermissionHelper / PermissionHelper

```php
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers\PermissionHelper;

// Find or create role
$role = PermissionHelper::findOrCreateRole('admin');

// Find or create permission
$permission = PermissionHelper::findOrCreatePermission('edit posts');

// Assign role to user
PermissionHelper::assignRoleToUser($user, 'admin');

// Give permission to user
PermissionHelper::givePermissionToUser($user, 'edit posts');

// Give permission to role
PermissionHelper::givePermissionToRole('admin', 'edit posts');

// Sync roles
PermissionHelper::syncRolesForUser($user, ['admin', 'editor']);

// Sync permissions
PermissionHelper::syncPermissionsForUser($user, ['edit posts', 'delete posts']);
```

### AuthHelper

```php
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers\AuthHelper;

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

## Using Directives / ការប្រើ Directives

See [Directives Guide](./05-directives.md) for information on using `@hasRole` and `@hasPermission` directives.
មើល [មគ្គុទេសក៍ Directives](./05-directives.md) សម្រាប់ព័ត៌មានអំពីការប្រើ directives `@hasRole` និង `@hasPermission`។

## Best Practices / Best Practices

1. **Use roles for broad access control** (admin, editor, viewer) / **ប្រើ roles សម្រាប់ការគ្រប់គ្រងការចូលប្រើប្រាស់ទូលំទូលាយ** (admin, editor, viewer)
2. **Use permissions for specific actions** (edit posts, delete users) / **ប្រើ permissions សម្រាប់ actions ជាក់លាក់** (edit posts, delete users)
3. **Assign permissions to roles** rather than individual users when possible / **ផ្តល់ permissions ទៅ roles** ជាជាង users បុគ្គលនៅពេលអាច
4. **Use descriptive permission names** (e.g., "edit posts" not "edit") / **ប្រើឈ្មោះ permissions ពិពណ៌នា** (ឧ. "edit posts" មិនមែន "edit")
5. **Regularly audit roles and permissions** to ensure security / **ធ្វើ audit roles និង permissions ជាទៀងទាត់** ដើម្បីធានាសុវត្ថិភាព

## Next Steps / ជំហានបន្ត

- Read [Directives Guide](./05-directives.md) / អាន [មគ្គុទេសក៍ Directives](./05-directives.md)
- Read [Helpers Guide](./08-helpers.md) / អាន [មគ្គុទេសក៍ Helpers](./08-helpers.md)

