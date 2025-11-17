# Traits and Models Documentation

Complete guide to using traits and models provided by this package.

## Traits

### HasApiTokens

Extends Sanctum's HasApiTokens trait with additional helper methods.

**Usage in User Model:**

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Traits\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    
    // ... rest of your model
}
```

**Available Methods:**

```php
// Create token with device information
$token = $user->createTokenWithDevice('mobile-app', ['*'], $ipAddress, $userAgent);

// Revoke all tokens except current
$deleted = $user->revokeOtherTokens();

// Check if user has valid token
if ($user->hasValidToken()) {
    // User has valid token
}
```

### HasTenant

Provides tenant-related functionality for models.

**Usage:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Traits\HasTenant;

class Post extends Model
{
    use HasTenant;
    
    // ... rest of your model
}
```

**Available Methods:**

```php
// Get tenant ID
$tenantId = $post->getTenantId();

// Set tenant ID
$post->setTenantId('tenant-123');

// Scope for specific tenant
$posts = Post::forTenant('tenant-123')->get();

// Scope for current tenant
$posts = Post::forCurrentTenant()->get();

// Check if belongs to tenant
if ($post->belongsToTenant('tenant-123')) {
    // Post belongs to tenant
}
```

### HasOwnership

Provides ownership-related functionality for models.

**Usage:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Traits\HasOwnership;

class Post extends Model
{
    use HasOwnership;
    
    // Optionally customize owner key
    protected $ownerKey = 'author_id';
    
    // ... rest of your model
}
```

**Available Methods:**

```php
// Get owner ID
$ownerId = $post->getOwnerId();

// Set owner ID
$post->setOwnerId($userId);

// Scope for specific user
$posts = Post::ownedBy($user)->get();

// Scope for current user
$posts = Post::ownedByCurrentUser()->get();

// Check if owned by user
if ($post->isOwnedBy($user)) {
    // Post is owned by user
}

// Check if owned by current user
if ($post->isOwnedByCurrentUser()) {
    // Post is owned by current user
}

// Assign ownership
$post->assignTo($user);
```

### HasAuditLog

Provides automatic audit logging for models.

**Usage:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Traits\HasAuditLog;

class Post extends Model
{
    use HasAuditLog;
    
    // ... rest of your model
}
```

**Features:**

- Automatically logs `created`, `updated`, and `deleted` events
- Stores user ID, IP address, user agent, and model data
- Can be disabled via configuration

**Available Methods:**

```php
// Manually log audit event
$post->logAuditEvent('custom_action', ['key' => 'value']);

// Get audit data
$auditData = $post->getAuditData();
```

### HasDevices

Provides device management functionality for user models.

**Usage:**

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Traits\HasDevices;

class User extends Authenticatable
{
    use HasDevices;
    
    // ... rest of your model
}
```

**Available Methods:**

```php
// Get all devices
$devices = $user->devices;

// Get active devices
$activeDevices = $user->activeDevices;

// Register new device
$device = $user->registerDevice('iPhone 14', $tokenId, $ipAddress, $userAgent);

// Remove device
$user->removeDevice($deviceId);

// Remove all devices
$user->removeAllDevices();

// Deactivate all devices
$user->deactivateAllDevices();

// Get device by token ID
$device = $user->getDeviceByTokenId($tokenId);

// Update device last used time
$user->touchDevice($tokenId);
```

## Models

### Device Model

Represents a device used for authentication.

**Relationships:**

```php
// Get device user
$user = $device->user;
```

**Scopes:**

```php
// Get active devices
$activeDevices = Device::active()->get();

// Get devices for user
$userDevices = Device::forUser($userId)->get();
```

**Methods:**

```php
// Activate device
$device->activate();

// Deactivate device
$device->deactivate();

// Update last used time
$device->touchLastUsed();
```

### AuditLog Model

Represents an audit log entry.

**Relationships:**

```php
// Get auditable model
$post = $auditLog->auditable;
```

**Scopes:**

```php
// Get logs for action
$createdLogs = AuditLog::forAction('created')->get();

// Get logs for user
$userLogs = AuditLog::forUser($userId)->get();

// Get logs for model
$postLogs = AuditLog::forModel(Post::class, $postId)->get();

// Get logs in date range
$logs = AuditLog::inDateRange($startDate, $endDate)->get();
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

class User extends Authenticatable
{
    use HasApiTokens, HasDevices, HasRoles;
    
    // ... your code
}

class Post extends Model
{
    use HasTenant, HasOwnership, HasAuditLog;
    
    protected $fillable = ['title', 'content', 'user_id', 'tenant_id'];
    
    // ... your code
}
```

## Migration Setup

Run the migrations to create the necessary tables:

```bash
php artisan migrate
```

Or publish and run:

```bash
php artisan vendor:publish --tag=lighthouse-sanctum-auth-migrations
php artisan migrate
```

## Configuration

Make sure to configure audit logging in `config/lighthouse-sanctum-auth.php`:

```php
'audit' => [
    'enabled' => true,
    'log_events' => [
        'created' => true,
        'updated' => true,
        'deleted' => true,
    ],
],
```

And configure the audit log channel in `config/logging.php`:

```php
'channels' => [
    'audit' => [
        'driver' => 'single',
        'path' => storage_path('logs/audit.log'),
        'level' => 'info',
    ],
],
```

