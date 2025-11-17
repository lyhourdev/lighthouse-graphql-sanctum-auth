# Device Management Guide / មគ្គុទេសក៍ការគ្រប់គ្រង Device

Complete guide to device management and tracking.
មគ្គុទេសក៍ពេញលេញសម្រាប់ការគ្រប់គ្រង និងតាមដាន device។

## Overview / ទិដ្ឋភាពទូទៅ

Device management allows you to track and manage devices used for authentication. This is useful for:
ការគ្រប់គ្រង device អនុញ្ញាតឱ្យអ្នកតាមដាន និងគ្រប់គ្រង devices ដែលប្រើសម្រាប់ authentication។ នេះមានប្រយោជន៍សម្រាប់:
- Security monitoring / ការតាមដាន security
- Session management / ការគ្រប់គ្រង session
- Device-based access control / ការគ្រប់គ្រងការចូលប្រើប្រាស់ផ្អែកលើ device
- Audit logging / Audit logging

## Configuration / ការកំណត់

Enable device management in `config/lighthouse-sanctum-auth.php`:
បើក device management ក្នុង `config/lighthouse-sanctum-auth.php`:

```php
'devices' => [
    'enabled' => true,
    'max_per_user' => 10,
],
```

## Setup / ការរៀបចំ

### Add HasDevices Trait to User Model / បន្ថែម HasDevices Trait ទៅ User Model

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

### Run Migration / ប្រតិបត្តិ Migration

```bash
php artisan migrate
```

This creates the `devices` table.
នេះនឹងបង្កើតតារាង `devices`។

## Automatic Device Registration / ការចុះឈ្មោះ Device ដោយស្វ័យប្រវត្តិ

When a user logs in, you can automatically register the device:
នៅពេល user login, អ្នកអាចចុះឈ្មោះ device ដោយស្វ័យប្រវត្តិ:

```php
use LeapLyhour\LighthouseGraphQLSanctumAuth\Auth\Services\LoginService;

$result = $loginService->login(
    credentials: ['email' => $email, 'password' => $password],
    deviceName: 'iPhone 14',
    ipAddress: request()->ip(),
    userAgent: request()->userAgent()
);

// Register device
$device = $user->registerDevice(
    name: 'iPhone 14',
    tokenId: $tokenId,
    ipAddress: request()->ip(),
    userAgent: request()->userAgent()
);
```

## Manual Device Management / ការគ្រប់គ្រង Device ដោយដៃ

### Register Device / ចុះឈ្មោះ Device

```php
$device = $user->registerDevice(
    name: 'MacBook Pro',
    tokenId: $tokenId,
    ipAddress: '192.168.1.1',
    userAgent: 'Mozilla/5.0...'
);
```

### Get All Devices / ទទួល Devices ទាំងអស់

```php
$devices = $user->devices;
```

### Get Active Devices / ទទួល Active Devices

```php
$activeDevices = $user->activeDevices;
```

### Remove Device / លុប Device

```php
$user->removeDevice($deviceId);
```

### Remove All Devices / លុប Devices ទាំងអស់

```php
$user->removeAllDevices();
```

### Deactivate All Devices / ធ្វើឱ្យ Devices ទាំងអស់ Inactive

```php
$user->deactivateAllDevices();
```

### Get Device by Token / ទទួល Device តាម Token

```php
$device = $user->getDeviceByTokenId($tokenId);
```

### Update Last Used Time / ធ្វើបច្ចុប្បន្នភាព Last Used Time

```php
$user->touchDevice($tokenId);
```

## Device Model / Device Model

### Relationships / Relationships

```php
// Get device user
// ទទួល user នៃ device
$user = $device->user;
```

### Scopes / Scopes

```php
// Get active devices
// ទទួល active devices
$activeDevices = Device::active()->get();

// Get devices for user
// ទទួល devices សម្រាប់ user
$userDevices = Device::forUser($userId)->get();
```

### Methods / Methods

```php
// Activate device
// ធ្វើឱ្យ device active
$device->activate();

// Deactivate device
// ធ្វើឱ្យ device inactive
$device->deactivate();

// Update last used time
// ធ្វើបច្ចុប្បន្នភាព last used time
$device->touchLastUsed();
```

## Device Limit / Device Limit

The package automatically enforces device limits. When a user exceeds the maximum number of devices, the oldest device is automatically deactivated.
Package បង្ខំ device limits ដោយស្វ័យប្រវត្តិ។ នៅពេល user លើសចំនួន device អតិបរមា, device ចាស់ជាងគេត្រូវបាន deactivate ដោយស្វ័យប្រវត្តិ។

**Configuration:** / **ការកំណត់:**
```php
'devices' => [
    'max_per_user' => 10,
],
```

## Using TokenHelper / ការប្រើ TokenHelper

```php
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers\TokenHelper;

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

## GraphQL Integration / ការរួមបញ្ចូល GraphQL

You can create GraphQL queries and mutations for device management:
អ្នកអាចបង្កើត GraphQL queries និង mutations សម្រាប់ device management:

```graphql
type Query {
  myDevices: [Device!]! @auth
}

type Mutation {
  removeDevice(id: ID!): Boolean! @auth
  deactivateDevice(id: ID!): Boolean! @auth
}
```

## Security Best Practices / Best Practices Security

1. **Track device information** - Store IP address and user agent / **តាមដាន device information** - រក្សាទុក IP address និង user agent
2. **Set device limits** - Prevent unlimited device registration / **កំណត់ device limits** - ការពារការចុះឈ្មោះ device មិនកំណត់
3. **Monitor device activity** - Track last used time / **តាមដាន device activity** - តាមដាន last used time
4. **Allow device revocation** - Let users remove devices / **អនុញ្ញាត device revocation** - អនុញ្ញាត users លុប devices
5. **Notify on new device** - Alert users when new device is registered / **ជូនដំណឹងនៅ device ថ្មី** - ជូនដំណឹង users នៅពេល device ថ្មីត្រូវបានចុះឈ្មោះ

## Example Implementation / ឧទាហរណ៍ការអនុវត្ត

### Login with Device Registration / Login ជាមួយ Device Registration

```php
<?php

namespace App\GraphQL\Mutations;

use LeapLyhour\LighthouseGraphQLSanctumAuth\Auth\Services\LoginService;
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers\TokenHelper;

final class Login
{
    public function __construct(
        private readonly LoginService $loginService
    ) {}

    public function __invoke(mixed $_, array $args, $context): array
    {
        $request = $context->request();
        
        $result = $this->loginService->login(
            credentials: [
                'email' => $args['email'],
                'password' => $args['password'],
            ],
            deviceName: $args['device_name'] ?? 'Unknown Device',
            ipAddress: $request?->ip(),
            userAgent: $request?->userAgent(),
        );
        
        $user = $result['user'];
        $token = $result['token'];
        
        // Extract token ID from token
        $tokenModel = TokenHelper::getCurrentToken();
        
        // Register device
        $user->registerDevice(
            name: $args['device_name'] ?? 'Unknown Device',
            tokenId: $tokenModel?->id,
            ipAddress: $request?->ip(),
            userAgent: $request?->userAgent()
        );
        
        return $result;
    }
}
```

## Next Steps / ជំហានបន្ត

- Read [Audit Logging Guide](./10-audit-logging.md) / អាន [មគ្គុទេសក៍ Audit Logging](./10-audit-logging.md)
- Read [Helpers Guide](./08-helpers.md) / អាន [មគ្គុទេសក៍ Helpers](./08-helpers.md)

