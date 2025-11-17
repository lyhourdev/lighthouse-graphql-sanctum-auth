# Authentication Guide / មគ្គុទេសក៍ Authentication

Complete guide to authentication with Lighthouse GraphQL Sanctum Auth.
មគ្គុទេសក៍ពេញលេញសម្រាប់ authentication ជាមួយ Lighthouse GraphQL Sanctum Auth។

## Overview / ទិដ្ឋភាពទូទៅ

This package provides GraphQL-based authentication using Laravel Sanctum. It supports both SPA and API token authentication.
Package នេះផ្តល់ authentication ដោយប្រើ GraphQL ជាមួយ Laravel Sanctum។ វាគាំទ្រ authentication ទាំង SPA និង API token។

## GraphQL Schema / GraphQL Schema

The package provides the following authentication operations:
Package ផ្តល់ operations authentication ខាងក្រោម:

### Queries / Queries

```graphql
type Query {
  me: User @auth
}
```

### Mutations / Mutations

```graphql
type Mutation {
  login(email: String!, password: String!, device_name: String): AuthPayload!
  refreshToken(refresh_token: String!): RefreshTokenPayload!
  logout: Boolean! @auth
}
```

## Login / ចូលប្រើប្រាស់

Authenticate a user and receive an access token.
Authenticate user និងទទួល access token។

### GraphQL Mutation / GraphQL Mutation

```graphql
mutation {
  login(
    email: "user@example.com"
    password: "password"
    device_name: "iPhone 14"
  ) {
    user {
      id
      name
      email
    }
    token
    token_type
  }
}
```

### Response / Response

```json
{
  "data": {
    "login": {
      "user": {
        "id": "1",
        "name": "John Doe",
        "email": "user@example.com"
      },
      "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
      "token_type": "Bearer"
    }
  }
}
```

### Using the Token / ការប្រើ Token

Include the token in the `Authorization` header:
រួមបញ្ចូល token ក្នុង header `Authorization`:

```
Authorization: Bearer 1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

## Get Current User / ទទួល User បច្ចុប្បន្ន

Retrieve the currently authenticated user.
ទទួល user ដែលបាន authenticate បច្ចុប្បន្ន។

### GraphQL Query

```graphql
query {
  me {
    id
    name
    email
    roles {
      name
    }
    permissions {
      name
    }
  }
}
```

### Response

```json
{
  "data": {
    "me": {
      "id": "1",
      "name": "John Doe",
      "email": "user@example.com",
      "roles": [
        {
          "name": "admin"
        }
      ],
      "permissions": [
        {
          "name": "edit posts"
        }
      ]
    }
  }
}
```

## Refresh Token / Refresh Token

Refresh an access token using a refresh token.
ធ្វើឱ្យ access token ថ្មីដោយប្រើ refresh token។

### GraphQL Mutation / GraphQL Mutation

```graphql
mutation {
  refreshToken(refresh_token: "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx") {
    user {
      id
      email
    }
    token
    token_type
  }
}
```

## Logout / Logout

Logout the current user and revoke all tokens.
Logout user បច្ចុប្បន្ន និងលុប tokens ទាំងអស់។

### GraphQL Mutation / GraphQL Mutation

```graphql
mutation {
  logout
}
```

### Response / Response

```json
{
  "data": {
    "logout": true
  }
}
```

## Using Helpers / ការប្រើ Helpers

You can use helper classes for authentication operations:
អ្នកអាចប្រើ helper classes សម្រាប់ operations authentication:

```php
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers\AuthHelper;

// Get authenticated user
$user = AuthHelper::user();

// Check if authenticated
if (AuthHelper::check()) {
    // User is authenticated
}

// Get user ID
$userId = AuthHelper::id();

// Check role
if (AuthHelper::hasRole('admin')) {
    // User has admin role
}

// Check permission
if (AuthHelper::can('edit posts')) {
    // User has permission
}
```

## Token Management / ការគ្រប់គ្រង Token

### Create Token / បង្កើត Token

```php
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers\TokenHelper;

$token = TokenHelper::createToken($user, 'mobile-app', ['read', 'write']);
```

### Revoke Token / លុប Token

```php
TokenHelper::revokeToken($tokenString);
```

### Revoke All Tokens / លុប Tokens ទាំងអស់

```php
TokenHelper::revokeAllTokens($user);
```

### Check Token Validity / ពិនិត្យមើល Token ត្រឹមត្រូវ

```php
if (TokenHelper::isValidToken($tokenString)) {
    // Token is valid
    // Token ត្រឹមត្រូវ
}
```

## Device Management / ការគ្រប់គ្រង Device

When logging in, you can register the device:
នៅពេល login, អ្នកអាចចុះឈ្មោះ device:

```php
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers\TokenHelper;

// After login, register device
$device = $user->registerDevice(
    name: 'iPhone 14',
    tokenId: $tokenId,
    ipAddress: request()->ip(),
    userAgent: request()->userAgent()
);
```

## Error Handling / ការដោះស្រាយ Errors

Authentication errors are returned as GraphQL errors:
Authentication errors ត្រូវបានត្រឡប់ជា GraphQL errors:

```json
{
  "errors": [
    {
      "message": "The provided credentials are incorrect.",
      "extensions": {
        "category": "validation"
      }
    }
  ]
}
```

## Security Best Practices / Best Practices Security

1. **Always use HTTPS** in production / **តែងតែប្រើ HTTPS** ក្នុង production
2. **Store tokens securely** on the client side / **រក្សាទុក tokens ដោយសុវត្ថិភាព** នៅ client side
3. **Implement token refresh** before expiration / **អនុវត្ត token refresh** មុនពេលផុតកំណត់
4. **Revoke tokens** on logout / **លុប tokens** នៅពេល logout
5. **Use device management** to track active sessions / **ប្រើ device management** ដើម្បីតាមដាន sessions active
6. **Implement IP filtering** for sensitive operations / **អនុវត្ត IP filtering** សម្រាប់ operations សំខាន់ៗ

## Next Steps / ជំហានបន្ត

- Read [Permissions & Roles Guide](./04-permissions-roles.md) / អាន [មគ្គុទេសក៍ Permissions & Roles](./04-permissions-roles.md)
- Read [Directives Guide](./05-directives.md) / អាន [មគ្គុទេសក៍ Directives](./05-directives.md)

