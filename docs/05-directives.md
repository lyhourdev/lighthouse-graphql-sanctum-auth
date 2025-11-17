# Directives Guide / មគ្គុទេសក៍ Directives

Complete guide to using enterprise directives in your GraphQL schema.
មគ្គុទេសក៍ពេញលេញសម្រាប់ការប្រើ enterprise directives ក្នុង GraphQL schema របស់អ្នក។

## Overview / ទិដ្ឋភាពទូទៅ

This package provides several enterprise-grade directives for security and data isolation:
Package នេះផ្តល់ directives enterprise-grade ជាច្រើនសម្រាប់ security និង data isolation:

- `@hasRole` - Role-based access control / ការគ្រប់គ្រងការចូលប្រើប្រាស់ផ្អែកលើ role
- `@hasPermission` - Permission-based access control / ការគ្រប់គ្រងការចូលប្រើប្រាស់ផ្អែកលើ permission
- `@ownership` - Resource ownership verification / ការផ្ទៀងផ្ទាត់ ownership resource
- `@belongsToTenant` - Multi-tenant isolation / ការញែក multi-tenant
- `@audit` - Audit logging / Audit logging

## @hasRole

Ensures the authenticated user has a specific role.
ធានាថា user ដែលបាន authenticate មាន role ជាក់លាក់។

### Usage

```graphql
type Query {
  adminUsers: [User!]! @hasRole(role: "admin")
}

type Mutation {
  deleteUser(id: ID!): User! @hasRole(role: "admin")
}
```

### Example

```graphql
type Query {
  # Only admins can access
  allUsers: [User!]! @hasRole(role: "admin")
  
  # Only moderators can access
  reportedPosts: [Post!]! @hasRole(role: "moderator")
}
```

### Error Response

If user doesn't have the required role:

```json
{
  "errors": [
    {
      "message": "Unauthorized: You must have the 'admin' role to access this field."
    }
  ]
}
```

## @hasPermission

Ensures the authenticated user has a specific permission.
ធានាថា user ដែលបាន authenticate មាន permission ជាក់លាក់។

### Usage / ការប្រើ

```graphql
type Mutation {
  deletePost(id: ID!): Post! @hasPermission(permission: "delete posts")
  
  publishPost(id: ID!): Post! @hasPermission(permission: "publish posts")
}
```

### Example / ឧទាហរណ៍

```graphql
type Mutation {
  # Requires specific permission
  # ត្រូវការ permission ជាក់លាក់
  editPost(id: ID!, input: PostInput!): Post!
    @hasPermission(permission: "edit posts")
  
  deletePost(id: ID!): Boolean!
    @hasPermission(permission: "delete posts")
}
```

### Error Response / Error Response

If user doesn't have the required permission:
ប្រសិនបើ user មិនមាន permission ដែលត្រូវការ:

```json
{
  "errors": [
    {
      "message": "Unauthorized: You must have the 'delete posts' permission to access this field."
    }
  ]
}
```

## @ownership

Ensures users can only access resources they own.
ធានាថា users អាចចូលប្រើ resources ដែលពួកគេជាម្ចាស់ប៉ុណ្ណោះ។

### Usage / ការប្រើ

```graphql
type Query {
  myPost(id: ID!): Post @ownership(relation: "user_id")
}

type Mutation {
  updateMyPost(id: ID!, input: PostInput!): Post!
    @ownership(relation: "user_id")
}
```

### Parameters / Parameters

- `relation` (optional, default: "user_id") - The field name that contains the owner ID / ឈ្មោះ field ដែលមាន owner ID

### Example / ឧទាហរណ៍

```graphql
type Query {
  # User can only access their own posts
  # User អាចចូលប្រើ posts របស់ពួកគេប៉ុណ្ណោះ
  myPost(id: ID!): Post @ownership(relation: "user_id")
  
  # User can only access their own comments
  # User អាចចូលប្រើ comments របស់ពួកគេប៉ុណ្ណោះ
  myComment(id: ID!): Comment @ownership(relation: "author_id")
}

type Mutation {
  # User can only update their own posts
  # User អាចធ្វើបច្ចុប្បន្នភាព posts របស់ពួកគេប៉ុណ្ណោះ
  updateMyPost(id: ID!, input: PostInput!): Post!
    @ownership(relation: "user_id")
}
```

### Error Response / Error Response

If user doesn't own the resource:
ប្រសិនបើ user មិនមែនជាម្ចាស់ resource:

```json
{
  "errors": [
    {
      "message": "Unauthorized: You do not own this resource."
    }
  ]
}
```

## @belongsToTenant

Ensures resources belong to the current tenant (multi-tenancy).
ធានាថា resources ជាកម្មសិទ្ធិរបស់ tenant បច្ចុប្បន្ន (multi-tenancy)។

### Usage / ការប្រើ

```graphql
type Query {
  posts: [Post!]! @belongsToTenant
}

type Mutation {
  createPost(input: PostInput!): Post! @belongsToTenant
}
```

### Parameters / Parameters

- `relation` (optional, default: "tenant_id") - The field name that contains the tenant ID / ឈ្មោះ field ដែលមាន tenant ID

### Example / ឧទាហរណ៍

```graphql
type Query {
  # Only returns posts for current tenant
  # ត្រឡប់ posts សម្រាប់ tenant បច្ចុប្បន្នប៉ុណ្ណោះ
  posts: [Post!]! @belongsToTenant
  
  # Custom tenant field name
  # ឈ្មោះ field tenant ផ្ទាល់ខ្លួន
  documents: [Document!]! @belongsToTenant(relation: "organization_id")
}
```

### Requirements / តម្រូវការ

- Multi-tenancy must be enabled in configuration / Multi-tenancy ត្រូវតែបើកក្នុង configuration
- Tenant must be resolvable (via header, domain, or token) / Tenant ត្រូវតែអាចដោះស្រាយបាន (តាម header, domain, ឬ token)

### Error Response / Error Response

If tenant cannot be resolved:
ប្រសិនបើ tenant មិនអាចដោះស្រាយបាន:

```json
{
  "errors": [
    {
      "message": "Tenant could not be resolved. Please ensure tenant identification is configured."
    }
  ]
}
```

## @audit

Logs field access and mutations for audit purposes.
កត់ត្រា field access និង mutations សម្រាប់ audit។

### Usage / ការប្រើ

```graphql
type Mutation {
  deleteUser(id: ID!): User! @audit(action: "delete")
  
  updatePost(id: ID!, input: PostInput!): Post! @audit(action: "update")
}
```

### Parameters / Parameters

- `action` (optional, default: "access") - The action being performed / Action ដែលកំពុងអនុវត្ត

### Example / ឧទាហរណ៍

```graphql
type Mutation {
  # Log creation
  # កត់ត្រា creation
  createPost(input: PostInput!): Post! @audit(action: "create")
  
  # Log update
  # កត់ត្រា update
  updatePost(id: ID!, input: PostInput!): Post! @audit(action: "update")
  
  # Log deletion
  # កត់ត្រា deletion
  deletePost(id: ID!): Boolean! @audit(action: "delete")
  
  # Log view
  # កត់ត្រា view
  viewPost(id: ID!): Post! @audit(action: "view")
}
```

### What Gets Logged / អ្វីដែលត្រូវបានកត់ត្រា

- User ID / User ID
- Action performed / Action ដែលបានអនុវត្ត
- Field name / ឈ្មោះ field
- IP address / IP address
- User agent / User agent
- Timestamp / Timestamp

## Combining Directives / ការរួមបញ្ចូល Directives

You can combine multiple directives:
អ្នកអាចរួមបញ្ចូល directives ច្រើន:

```graphql
type Mutation {
  # Requires permission AND logs the action
  # ត្រូវការ permission និងកត់ត្រា action
  deletePost(id: ID!): Post!
    @hasPermission(permission: "delete posts")
    @audit(action: "delete")
  
  # Requires role AND ownership AND logs
  # ត្រូវការ role និង ownership និងកត់ត្រា
  updateMyPost(id: ID!, input: PostInput!): Post!
    @hasRole(role: "editor")
    @ownership(relation: "user_id")
    @audit(action: "update")
}
```

## Complete Example / ឧទាហរណ៍ពេញលេញ

```graphql
type Query {
  # Public access
  # ការចូលប្រើប្រាស់សាធារណៈ
  posts: [Post!]! @paginate
  
  # Requires authentication
  # ត្រូវការ authentication
  myPosts: [Post!]! @auth @ownership(relation: "user_id")
  
  # Requires admin role
  # ត្រូវការ admin role
  allUsers: [User!]! @hasRole(role: "admin")
  
  # Requires permission
  # ត្រូវការ permission
  draftPosts: [Post!]! @hasPermission(permission: "view drafts")
  
  # Multi-tenant
  # Multi-tenant
  tenantPosts: [Post!]! @belongsToTenant
}

type Mutation {
  # Create with audit
  # បង្កើតជាមួយ audit
  createPost(input: PostInput!): Post!
    @hasPermission(permission: "create posts")
    @audit(action: "create")
  
  # Update with ownership check
  # ធ្វើបច្ចុប្បន្នភាពជាមួយ ownership check
  updatePost(id: ID!, input: PostInput!): Post!
    @hasPermission(permission: "edit posts")
    @ownership(relation: "user_id")
    @audit(action: "update")
  
  # Delete with role and audit
  # លុបជាមួយ role និង audit
  deletePost(id: ID!): Boolean!
    @hasRole(role: "admin")
    @audit(action: "delete")
}
```

## Best Practices / Best Practices

1. **Use `@auth` first** to ensure user is authenticated / **ប្រើ `@auth` មុន** ដើម្បីធានាថា user បាន authenticate
2. **Use `@hasRole` for broad access** (admin, moderator) / **ប្រើ `@hasRole` សម្រាប់ការចូលប្រើប្រាស់ទូលំទូលាយ** (admin, moderator)
3. **Use `@hasPermission` for specific actions** (edit posts, delete users) / **ប្រើ `@hasPermission` សម្រាប់ actions ជាក់លាក់** (edit posts, delete users)
4. **Use `@ownership` for user-owned resources** / **ប្រើ `@ownership` សម្រាប់ resources ដែល user ជាម្ចាស់**
5. **Use `@belongsToTenant` for multi-tenant isolation** / **ប្រើ `@belongsToTenant` សម្រាប់ការញែក multi-tenant**
6. **Use `@audit` for important operations** (create, update, delete) / **ប្រើ `@audit` សម្រាប់ operations សំខាន់ៗ** (create, update, delete)

## Next Steps / ជំហានបន្ត

- Read [Multi-Tenancy Guide](./06-multi-tenancy.md) / អាន [មគ្គុទេសក៍ Multi-Tenancy](./06-multi-tenancy.md)
- Read [Helpers Guide](./08-helpers.md) / អាន [មគ្គុទេសក៍ Helpers](./08-helpers.md)

