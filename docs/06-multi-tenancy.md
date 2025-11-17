# Multi-Tenancy Guide / មគ្គុទេសក៍ Multi-Tenancy

Complete guide to implementing multi-tenancy with this package.
មគ្គុទេសក៍ពេញលេញសម្រាប់ការអនុវត្ត multi-tenancy ជាមួយ package នេះ។

## Overview / ទិដ្ឋភាពទូទៅ

This package supports multi-tenancy with three resolver methods:
Package នេះគាំទ្រ multi-tenancy ជាមួយវិធីសាស្ត្រដោះស្រាយបី:
- **Header-based** - Resolve tenant from HTTP header / ដោះស្រាយ tenant ពី HTTP header
- **Domain-based** - Resolve tenant from subdomain / ដោះស្រាយ tenant ពី subdomain
- **Token-based** - Resolve tenant from authenticated user / ដោះស្រាយ tenant ពី user ដែលបាន authenticate

## Configuration

Enable multi-tenancy in `config/lighthouse-sanctum-auth.php`:

```php
'tenancy' => [
    'enabled' => true,
    'resolver' => 'header', // header, domain, or token
    'header_name' => 'X-Tenant-ID',
    'database_strategy' => 'single_db', // single_db or multi_db
],
```

## Resolver Methods

### Header-Based (Recommended)

Resolve tenant from HTTP header.

**Configuration:**
```php
'resolver' => 'header',
'header_name' => 'X-Tenant-ID',
```

**Usage:**
```http
GET /graphql
X-Tenant-ID: tenant-123
Authorization: Bearer token...
```

### Domain-Based

Resolve tenant from subdomain.

**Configuration:**
```php
'resolver' => 'domain',
```

**Usage:**
```
https://tenant-123.example.com/graphql
```

The subdomain (`tenant-123`) will be used as the tenant ID.

### Token-Based

Resolve tenant from authenticated user's token.

**Configuration:**
```php
'resolver' => 'token',
```

**Requirements:**
- User model must have `tenant_id` attribute or `getTenantId()` method
- User must be authenticated

## Using HasTenant Trait / ការប្រើ HasTenant Trait

Add the `HasTenant` trait to your models:
បន្ថែម trait `HasTenant` ទៅ models របស់អ្នក:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Traits\HasTenant;

class Post extends Model
{
    use HasTenant;
    
    protected $fillable = ['title', 'content', 'tenant_id'];
}
```

### Available Methods / Methods ដែលមាន

```php
// Get tenant ID
// ទទួល tenant ID
$tenantId = $post->getTenantId();

// Set tenant ID
// កំណត់ tenant ID
$post->setTenantId('tenant-123');

// Scope for specific tenant
// Scope សម្រាប់ tenant ជាក់លាក់
$posts = Post::forTenant('tenant-123')->get();

// Scope for current tenant
// Scope សម្រាប់ tenant បច្ចុប្បន្ន
$posts = Post::forCurrentTenant()->get();

// Check if belongs to tenant
// ពិនិត្យមើលថាជាកម្មសិទ្ធិរបស់ tenant
if ($post->belongsToTenant('tenant-123')) {
    // Post belongs to tenant
    // Post ជាកម្មសិទ្ធិរបស់ tenant
}
```

## Using @belongsToTenant Directive / ការប្រើ @belongsToTenant Directive

Automatically filter resources by tenant:
ច្រោះ resources ដោយ tenant ដោយស្វ័យប្រវត្តិ:

```graphql
type Query {
  posts: [Post!]! @belongsToTenant
}

type Mutation {
  createPost(input: PostInput!): Post! @belongsToTenant
}
```

### Custom Relation Field / Relation Field ផ្ទាល់ខ្លួន

```graphql
type Query {
  documents: [Document!]! @belongsToTenant(relation: "organization_id")
}
```

## Using TenantHelper / ការប្រើ TenantHelper

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

## Database Strategies / យុទ្ធសាស្ត្រ Database

### Single Database (single_db) / Database តែមួយ (single_db)

All tenants share the same database. Data is isolated using `tenant_id` column.
Tenants ទាំងអស់ប្រើ database ដូចគ្នា។ Data ត្រូវបានញែកដោយប្រើ column `tenant_id`។

**Advantages:** / **គុណសម្បត្តិ:**
- Simple setup / ការកំណត់សាមញ្ញ
- Easy backup/restore / ការបម្រុងទុក/ស្ដារងាយ
- Lower infrastructure costs / ថ្លៃ infrastructure ទាប

**Disadvantages:** / **គុណវិបត្តិ:**
- Potential performance issues with large datasets / បញ្ហា performance ដែលអាចកើតឡើងជាមួយ datasets ធំ
- All tenants share same database resources / Tenants ទាំងអស់ប្រើ resources database ដូចគ្នា

**Migration Example:** / **ឧទាហរណ៍ Migration:**
```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('content');
    $table->string('tenant_id')->index();
    $table->timestamps();
});
```

### Multi-Database (multi_db) / Multi-Database (multi_db)

Each tenant has its own database.
Tenant នីមួយៗមាន database ផ្ទាល់ខ្លួន។

**Advantages:** / **គុណសម្បត្តិ:**
- Complete data isolation / ការញែក data ពេញលេញ
- Better performance / Performance ល្អជាង
- Easier scaling / Scaling ងាយ

**Disadvantages:** / **គុណវិបត្តិ:**
- More complex setup / ការកំណត់ស្មុគ្រស្មាជាង
- Higher infrastructure costs / ថ្លៃ infrastructure ខ្ពស់
- More complex backup/restore / ការបម្រុងទុក/ស្ដារ ស្មុគ្រស្មាជាង

**Implementation:** / **ការអនុវត្ត:**
You'll need to implement custom database switching logic based on tenant.
អ្នកនឹងត្រូវអនុវត្ត logic database switching ផ្ទាល់ខ្លួនផ្អែកលើ tenant។

## Complete Example / ឧទាហរណ៍ពេញលេញ

### Model / Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Traits\HasTenant;
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Traits\HasOwnership;

class Post extends Model
{
    use HasTenant, HasOwnership;
    
    protected $fillable = ['title', 'content', 'user_id', 'tenant_id'];
}
```

### GraphQL Schema / GraphQL Schema

```graphql
type Query {
  # Only returns posts for current tenant
  # ត្រឡប់ posts សម្រាប់ tenant បច្ចុប្បន្នប៉ុណ្ណោះ
  posts: [Post!]! @belongsToTenant
  
  # User's posts in current tenant
  # Posts របស់ user ក្នុង tenant បច្ចុប្បន្ន
  myPosts: [Post!]! 
    @auth
    @belongsToTenant
    @ownership(relation: "user_id")
}

type Mutation {
  # Create post in current tenant
  # បង្កើត post ក្នុង tenant បច្ចុប្បន្ន
  createPost(input: PostInput!): Post!
    @auth
    @belongsToTenant
    @hasPermission(permission: "create posts")
}
```

### Resolver / Resolver

```php
<?php

namespace App\GraphQL\Mutations;

use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers\TenantHelper;

final class CreatePost
{
    public function __invoke(mixed $_, array $args, $context)
    {
        $tenantId = TenantHelper::currentTenantId();
        
        return Post::create([
            'title' => $args['input']['title'],
            'content' => $args['input']['content'],
            'user_id' => auth()->id(),
            'tenant_id' => $tenantId,
        ]);
    }
}
```

## Security Considerations / ការពិចារណា Security

1. **Always validate tenant access** - Ensure users can only access their tenant's data / **តែងតែ validate tenant access** - ធានាថា users អាចចូលប្រើ data របស់ tenant របស់ពួកគេប៉ុណ្ណោះ
2. **Use `@belongsToTenant` directive** - Automatically filters by tenant / **ប្រើ `@belongsToTenant` directive** - ច្រោះដោយ tenant ដោយស្វ័យប្រវត្តិ
3. **Validate tenant in resolvers** - Double-check tenant access in critical operations / **Validate tenant ក្នុង resolvers** - ពិនិត្យ tenant access ទ្វេដងក្នុង operations សំខាន់ៗ
4. **Use middleware** - Add tenant validation middleware if needed / **ប្រើ middleware** - បន្ថែម tenant validation middleware ប្រសិនបើត្រូវការ
5. **Audit tenant access** - Log tenant access for security auditing / **Audit tenant access** - កត់ត្រា tenant access សម្រាប់ security auditing

## Best Practices / Best Practices

1. **Use header-based resolution** for API clients / **ប្រើ header-based resolution** សម្រាប់ API clients
2. **Use domain-based resolution** for web applications / **ប្រើ domain-based resolution** សម្រាប់ web applications
3. **Always set tenant_id** when creating records / **តែងតែកំណត់ tenant_id** នៅពេលបង្កើត records
4. **Use scopes** to filter by tenant automatically / **ប្រើ scopes** ដើម្បីច្រោះដោយ tenant ដោយស្វ័យប្រវត្តិ
5. **Test tenant isolation** thoroughly / **សាកល្បង tenant isolation** ដោយហ្មត់ចត់

## Next Steps / ជំហានបន្ត

- Read [Traits & Models Guide](./09-traits-models.md) / អាន [មគ្គុទេសក៍ Traits & Models](./09-traits-models.md)
- Read [Helpers Guide](./08-helpers.md) / អាន [មគ្គុទេសក៍ Helpers](./08-helpers.md)

