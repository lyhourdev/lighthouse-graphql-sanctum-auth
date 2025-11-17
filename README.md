# Lighthouse GraphQL Sanctum Auth / Lighthouse GraphQL Sanctum Auth

Enterprise-grade Laravel Package that provides Laravel Sanctum Authentication with Lighthouse GraphQL, Spatie Laravel Permission, Multi-Tenant Support, 2FA, Refresh Token System, Device Management, Audit Logging, IP Filtering, Ownership & Row-Level Security.

Laravel Package Enterprise-grade ដែលផ្តល់ Laravel Sanctum Authentication ជាមួយ Lighthouse GraphQL, Spatie Laravel Permission, Multi-Tenant Support, 2FA, Refresh Token System, Device Management, Audit Logging, IP Filtering, Ownership & Row-Level Security។

## Features / Features

- ✅ Laravel Sanctum Authentication (SPA + Token) / Authentication Sanctum (SPA + Token)
- ✅ Lighthouse GraphQL (Queries, Mutations, Directives) / Lighthouse GraphQL (Queries, Mutations, Directives)
- ✅ Spatie Laravel Permission (Roles & Permissions) / Spatie Permission (Roles & Permissions)
- ✅ Multi-Tenant Support (Single DB + Multi DB) / ការគាំទ្រ Multi-Tenant (Single DB + Multi DB)
- ✅ 2FA (Google Authenticator) / 2FA (Google Authenticator) - *Planned* / *គ្រោង*
- ✅ Refresh Token System / ប្រព័ន្ធ Refresh Token
- ✅ Device Management / ការគ្រប់គ្រង Device
- ✅ Audit Logging System / ប្រព័ន្ធ Audit Logging
- ✅ IP Filtering / ការច្រោះ IP - *Planned* / *គ្រោង*
- ✅ Ownership & Row-Level Security / Ownership & Row-Level Security
- ✅ Tenant Resolver (Domain, Header, Token) / Tenant Resolver (Domain, Header, Token)
- ✅ Enterprise Directives / Directives Enterprise:
  - `@hasRole` - Role-based access control / ការគ្រប់គ្រងការចូលប្រើប្រាស់ផ្អែកលើ role
  - `@hasPermission` - Permission-based access control / ការគ្រប់គ្រងការចូលប្រើប្រាស់ផ្អែកលើ permission
  - `@ownership` - Resource ownership verification / ការផ្ទៀងផ្ទាត់ ownership resource
  - `@belongsToTenant` - Multi-tenant isolation / ការញែក multi-tenant
  - `@audit` - Audit logging / Audit logging

## Installation / ការដំឡើង

```bash
composer require leap-lyhour/lighthouse-graphql-sanctum-auth
```

### Quick Start / ចាប់ផ្តើមរហ័ស

```bash
# Install package
# ដំឡើង package
composer require leap-lyhour/lighthouse-graphql-sanctum-auth

# Publish configuration
# Publish configuration
php artisan vendor:publish --tag=lighthouse-sanctum-auth-config

# Publish migrations
# Publish migrations
php artisan vendor:publish --tag=lighthouse-sanctum-auth-migrations

# Run migrations
# ប្រតិបត្តិ migrations
php artisan migrate

# Seed permissions and roles (optional)
# Seed permissions និង roles (ជម្រើស)
php artisan db:seed --class="LeapLyhour\\LighthouseGraphQLSanctumAuth\\Database\\Seeders\\PermissionSeeder"
```

## Configuration / ការកំណត់

Publish the configuration file:
Publish file កំណត់:

```bash
php artisan vendor:publish --tag=lighthouse-sanctum-auth-config
```

This will create `config/lighthouse-sanctum-auth.php` in your application.
នេះនឹងបង្កើត `config/lighthouse-sanctum-auth.php` ក្នុង application របស់អ្នក។

For detailed configuration options, see [Configuration Guide](./docs/02-configuration.md).
សម្រាប់ការកំណត់លម្អិត, មើល [មគ្គុទេសក៍ការកំណត់](./docs/02-configuration.md)។

## Usage / ការប្រើ

### GraphQL Schema / GraphQL Schema

The package provides authentication mutations and queries:
Package ផ្តល់ authentication mutations និង queries:

```graphql
type Query {
  me: User @auth
}

type Mutation {
  login(email: String!, password: String!, device_name: String): AuthPayload!
  refreshToken(refresh_token: String!): RefreshTokenPayload!
  logout: Boolean! @auth
}
```

### Authentication Example / ឧទាហរណ៍ Authentication

```graphql
# Login
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
      roles {
        name
      }
      permissions {
        name
      }
    }
    token
    token_type
  }
}

# Get current user
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

### Directives / Directives

#### @hasRole

Restrict access to users with specific roles:
កំណត់ការចូលប្រើប្រាស់ទៅ users ដែលមាន roles ជាក់លាក់:

```graphql
type Query {
  adminUsers: [User!]! @hasRole(role: "admin")
}

type Mutation {
  deleteUser(id: ID!): User! @hasRole(role: "admin")
}
```

#### @hasPermission

Restrict access to users with specific permissions:
កំណត់ការចូលប្រើប្រាស់ទៅ users ដែលមាន permissions ជាក់លាក់:

```graphql
type Mutation {
  deleteUser(id: ID!): User! @hasPermission(permission: "delete users")
  
  editPost(id: ID!, input: PostInput!): Post! 
    @hasPermission(permission: "edit posts")
}
```

#### @ownership

Ensure users can only access resources they own:
ធានាថា users អាចចូលប្រើ resources ដែលពួកគេជាម្ចាស់ប៉ុណ្ណោះ:

```graphql
type Query {
  myPost(id: ID!): Post @ownership(relation: "user_id")
}

type Mutation {
  updateMyPost(id: ID!, input: PostInput!): Post!
    @ownership(relation: "user_id")
}
```

#### @belongsToTenant

Ensure resources belong to the current tenant:
ធានាថា resources ជាកម្មសិទ្ធិរបស់ tenant បច្ចុប្បន្ន:

```graphql
type Query {
  posts: [Post!]! @belongsToTenant
}

type Mutation {
  createPost(input: PostInput!): Post! @belongsToTenant
}
```

#### @audit

Log field access and mutations:
កត់ត្រា field access និង mutations:

```graphql
type Mutation {
  deleteUser(id: ID!): User! @audit(action: "delete")
  
  createPost(input: PostInput!): Post! @audit(action: "create")
  
  updatePost(id: ID!, input: PostInput!): Post! @audit(action: "update")
}
```

### Combining Directives / ការរួមបញ្ចូល Directives

You can combine multiple directives for enhanced security:
អ្នកអាចរួមបញ្ចូល directives ច្រើនសម្រាប់ security កាន់តែប្រសើរ:

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

## Requirements / តម្រូវការ

- PHP >= 8.2
- Laravel >= 12.0
- Lighthouse >= 6.0
- Sanctum >= 4.0
- Spatie Permission >= 6.0

## Documentation / Documentation

Complete documentation is available in the [`docs`](./docs/) directory:
Documentation ពេញលេញមាននៅក្នុង directory [`docs`](./docs/):

- 📖 [Installation Guide](./docs/01-installation.md) / [មគ្គុទេសក៍ការដំឡើង](./docs/01-installation.md)
- ⚙️ [Configuration Guide](./docs/02-configuration.md) / [មគ្គុទេសក៍ការកំណត់](./docs/02-configuration.md)
- 🔐 [Authentication Guide](./docs/03-authentication.md) / [មគ្គុទេសក៍ Authentication](./docs/03-authentication.md)
- 👥 [Permissions & Roles Guide](./docs/04-permissions-roles.md) / [មគ្គុទេសក៍ Permissions & Roles](./docs/04-permissions-roles.md)
- 🎯 [Directives Guide](./docs/05-directives.md) / [មគ្គុទេសក៍ Directives](./docs/05-directives.md)
- 🏢 [Multi-Tenancy Guide](./docs/06-multi-tenancy.md) / [មគ្គុទេសក៍ Multi-Tenancy](./docs/06-multi-tenancy.md)
- 📱 [Device Management Guide](./docs/07-device-management.md) / [មគ្គុទេសក៍ការគ្រប់គ្រង Device](./docs/07-device-management.md)
- 🛠️ [Helpers Guide](./docs/08-helpers.md) / [មគ្គុទេសក៍ Helpers](./docs/08-helpers.md)
- 🔧 [Traits & Models Guide](./docs/09-traits-models.md) / [មគ្គុទេសក៍ Traits & Models](./docs/09-traits-models.md)
- 📋 [Audit Logging Guide](./docs/10-audit-logging.md) / [មគ្គុទេសក៍ Audit Logging](./docs/10-audit-logging.md)
- 💻 [Frontend Integration](./docs/14-frontend-integration.md) / [ការរួមបញ្ចូល Frontend](./docs/14-frontend-integration.md)
- 📚 [API Reference](./docs/12-api-reference.md) / [ឯកសារ API](./docs/12-api-reference.md)
- 🐛 [Troubleshooting](./docs/13-troubleshooting.md) / [ការដោះស្រាយបញ្ហា](./docs/13-troubleshooting.md)

## Quick Examples / ឧទាហរណ៍រហ័ស

### User Model Setup / ការរៀបចំ User Model

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Traits\HasDevices;

class User extends Authenticatable
{
    use HasApiTokens, HasRoles, HasDevices;
    
    // ... your code
}
```

### Using Helpers / ការប្រើ Helpers

```php
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers\AuthHelper;
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers\PermissionHelper;

// Check authentication
// ពិនិត្យ authentication
if (AuthHelper::check()) {
    $user = AuthHelper::user();
}

// Check role
// ពិនិត្យ role
if (AuthHelper::hasRole('admin')) {
    // User is admin
}

// Check permission
// ពិនិត្យ permission
if (AuthHelper::hasPermission('edit posts')) {
    // User can edit posts
}

// Find or create role
// រក ឬបង្កើត role
$role = PermissionHelper::findOrCreateRole('editor');
```

## Testing / ការធ្វើតេស្ត

```bash
# Run tests
# ប្រតិបត្តិ tests
composer test

# Run tests with coverage
# ប្រតិបត្តិ tests ជាមួយ coverage
composer test-coverage
```

## Contributing / ការរួមចំណែក

Contributions are welcome! Please feel free to submit a Pull Request.
ការរួមចំណែកត្រូវបានស្វាគមន៍! សូម submit Pull Request។

## Changelog / ប្រវត្តិការផ្លាស់ប្តូរ

Please see [CHANGELOG.md](CHANGELOG.md) for more information on what has changed recently.
សូមមើល [CHANGELOG.md](CHANGELOG.md) សម្រាប់ព័ត៌មានបន្ថែមអំពីការផ្លាស់ប្តូរថ្មីៗ។

## Security / សុវត្ថិភាព

If you discover any security-related issues, please email `leaplyhour2013@gmail.com` instead of using the issue tracker.
ប្រសិនបើអ្នករកឃើញបញ្ហាទាក់ទងនឹងសុវត្ថិភាព, សូមផ្ញើ email ទៅ `leaplyhour2013@gmail.com` ជំនួសឱ្យការប្រើ issue tracker។

## Credits / ការទទួលស្គាល់

- **Author:** Leap Lyhour
- **Email:** leaplyhour2013@gmail.com
- **License:** MIT

## License / អាជ្ញាប័ណ្ឌ

This package is open-sourced software licensed under the [MIT license](LICENSE).
Package នេះជា open-sourced software ដែលមាន license ក្រោម [MIT license](LICENSE)។

