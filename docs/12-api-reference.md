# API Reference / ឯកសារ API

Complete API reference for all classes and methods.
ឯកសារ API ពេញលេញសម្រាប់ classes និង methods ទាំងអស់។

## Directives / Directives

### @hasRole

```graphql
directive @hasRole(role: String!) on FIELD_DEFINITION
```

### @hasPermission

```graphql
directive @hasPermission(permission: String!) on FIELD_DEFINITION
```

### @ownership

```graphql
directive @ownership(relation: String = "user_id") on FIELD_DEFINITION
```

### @belongsToTenant

```graphql
directive @belongsToTenant(relation: String = "tenant_id") on FIELD_DEFINITION
```

### @audit

```graphql
directive @audit(action: String = "access") on FIELD_DEFINITION
```

## Helpers

See [Helpers Guide](./08-helpers.md) for complete helper reference.

## Traits

See [Traits & Models Guide](./09-traits-models.md) for complete trait reference.

## Models

### Device

**Table:** `devices`

**Relationships:**
- `user()` - BelongsTo User

**Scopes:**
- `active()` - Active devices
- `forUser($userId)` - Devices for user

**Methods:**
- `activate()` - Activate device
- `deactivate()` - Deactivate device
- `touchLastUsed()` - Update last used time

### AuditLog

**Table:** `audit_logs`

**Relationships:**
- `auditable()` - MorphTo (polymorphic)

**Scopes:**
- `forAction($action)` - Logs for action
- `forUser($userId)` - Logs for user
- `forModel($modelType, $modelId)` - Logs for model
- `inDateRange($startDate, $endDate)` - Logs in date range

## GraphQL Types

### User

```graphql
type User {
  id: ID!
  name: String!
  email: String!
  roles: [Role!]!
  permissions: [Permission!]!
}
```

### Role

```graphql
type Role {
  id: ID!
  name: String!
  guard_name: String!
  permissions: [Permission!]!
  users: [User!]!
}
```

### Permission

```graphql
type Permission {
  id: ID!
  name: String!
  guard_name: String!
  roles: [Role!]!
  users: [User!]!
}
```

### AuthPayload

```graphql
type AuthPayload {
  user: User!
  token: String!
  token_type: String!
}
```

## Mutations

### login

```graphql
login(
  email: String!
  password: String!
  device_name: String
): AuthPayload!
```

### refreshToken

```graphql
refreshToken(refresh_token: String!): RefreshTokenPayload!
```

### logout

```graphql
logout: Boolean!
```

## Queries

### me

```graphql
me: User
```

### roles

```graphql
roles: [Role!]!
```

### permissions

```graphql
permissions: [Permission!]!
```

## Next Steps

- Read [Troubleshooting](./13-troubleshooting.md)

