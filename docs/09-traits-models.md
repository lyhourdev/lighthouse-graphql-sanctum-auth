# Traits & Models Guide / មគ្គុទេសក៍ Traits & Models

Complete guide to using traits and models provided by this package.
មគ្គុទេសក៍ពេញលេញសម្រាប់ការប្រើ traits និង models ដែល package នេះផ្តល់។

## Traits / Traits

### HasApiTokens

Extends Sanctum's HasApiTokens with additional methods.
ពង្រីក Sanctum's HasApiTokens ជាមួយ methods បន្ថែម។

**Usage:**
```php
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Traits\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
}
```

**Methods:**
- `createTokenWithDevice()` - Create token with device info
- `revokeOtherTokens()` - Revoke all tokens except current
- `hasValidToken()` - Check if user has valid token

### HasTenant

Provides tenant-related functionality.

**Usage:**
```php
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Traits\HasTenant;

class Post extends Model
{
    use HasTenant;
}
```

**Methods:**
- `getTenantId()` / `setTenantId()` - Get/set tenant ID
- `scopeForTenant()` - Scope for specific tenant
- `scopeForCurrentTenant()` - Scope for current tenant
- `belongsToTenant()` - Check tenant ownership

### HasOwnership

Provides ownership-related functionality.

**Usage:**
```php
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Traits\HasOwnership;

class Post extends Model
{
    use HasOwnership;
    
    // Optional: customize owner key
    protected $ownerKey = 'author_id';
}
```

**Methods:**
- `getOwnerId()` / `setOwnerId()` - Get/set owner ID
- `scopeOwnedBy()` - Scope for specific user
- `scopeOwnedByCurrentUser()` - Scope for current user
- `isOwnedBy()` / `isOwnedByCurrentUser()` - Check ownership
- `assignTo()` - Assign ownership

### HasAuditLog

Provides automatic audit logging.

**Usage:**
```php
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Traits\HasAuditLog;

class Post extends Model
{
    use HasAuditLog;
}
```

**Features:**
- Automatically logs `created`, `updated`, `deleted` events
- Stores user ID, IP address, user agent, and model data

**Methods:**
- `logAuditEvent()` - Manually log audit event
- `getAuditData()` - Get audit data

### HasDevices

Provides device management functionality.

**Usage:**
```php
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Traits\HasDevices;

class User extends Authenticatable
{
    use HasDevices;
}
```

**Methods:**
- `devices()` / `activeDevices()` - Relationships
- `registerDevice()` - Register new device
- `removeDevice()` / `removeAllDevices()` - Remove devices
- `deactivateAllDevices()` - Deactivate devices
- `getDeviceByTokenId()` - Find device by token
- `touchDevice()` - Update last used time

## Models

### Device Model

Represents a device used for authentication.

**Relationships:**
```php
$user = $device->user;
```

**Scopes:**
```php
Device::active()->get();
Device::forUser($userId)->get();
```

**Methods:**
```php
$device->activate();
$device->deactivate();
$device->touchLastUsed();
```

### AuditLog Model

Represents an audit log entry.

**Relationships:**
```php
$post = $auditLog->auditable;
```

**Scopes:**
```php
AuditLog::forAction('created')->get();
AuditLog::forUser($userId)->get();
AuditLog::forModel(Post::class, $postId)->get();
AuditLog::inDateRange($startDate, $endDate)->get();
```

## Complete Example

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Traits\{
    HasApiTokens,
    HasDevices
};
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Traits\{
    HasTenant,
    HasOwnership,
    HasAuditLog
};

class User extends Authenticatable
{
    use HasApiTokens, HasDevices, HasRoles;
}

class Post extends Model
{
    use HasTenant, HasOwnership, HasAuditLog;
    
    protected $fillable = ['title', 'content', 'user_id', 'tenant_id'];
}
```

## Next Steps

- Read [Examples](./11-examples.md)
- Read [API Reference](./12-api-reference.md)

