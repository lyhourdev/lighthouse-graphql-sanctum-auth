# Installation Guide / មគ្គុទេសក៍ការដំឡើង

Complete installation guide for Lighthouse GraphQL Sanctum Auth package.
មគ្គុទេសក៍ការដំឡើងពេញលេញសម្រាប់ package Lighthouse GraphQL Sanctum Auth។

## Requirements / តម្រូវការ

- PHP >= 8.2
- Laravel >= 12.0
- Composer
- Lighthouse GraphQL >= 6.0
- Laravel Sanctum >= 4.0
- Spatie Laravel Permission >= 6.0

## Installation / ការដំឡើង

### Step 1: Install via Composer / ជំហានទី ១: ដំឡើងតាម Composer

```bash
composer require leap-lyhour/lighthouse-graphql-sanctum-auth
```

### Step 2: Publish Configuration / ជំហានទី ២: Publish Configuration

```bash
php artisan vendor:publish --tag=lighthouse-sanctum-auth-config
```

This will create `config/lighthouse-sanctum-auth.php` in your application.
នេះនឹងបង្កើត `config/lighthouse-sanctum-auth.php` ក្នុង application របស់អ្នក។

### Step 3: Publish Migrations / ជំហានទី ៣: Publish Migrations

```bash
php artisan vendor:publish --tag=lighthouse-sanctum-auth-migrations
```

### Step 4: Run Migrations / ជំហានទី ៤: ប្រតិបត្តិ Migrations

```bash
php artisan migrate
```

This will create the following tables:
នេះនឹងបង្កើតតារាងខាងក្រោម:
- `devices` - For device management / សម្រាប់ការគ្រប់គ្រង device
- `audit_logs` - For audit logging / សម្រាប់ audit logging

### Step 5: Configure User Model / ជំហានទី ៥: កំណត់ User Model

Add the required traits to your User model:
បន្ថែម traits ដែលត្រូវការទៅ User model របស់អ្នក:

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
    
    // ... rest of your model
}
```

### Step 6: Configure Auth Guard / ជំហានទី ៦: កំណត់ Auth Guard

Update `config/auth.php` to include Sanctum guard:
ធ្វើបច្ចុប្បន្នភាព `config/auth.php` ដើម្បីរួមបញ្ចូល Sanctum guard:

```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    'sanctum' => [
        'driver' => 'sanctum',
        'provider' => 'users',
    ],
],
```

### Step 7: Configure Lighthouse / ជំហានទី ៧: កំណត់ Lighthouse

Ensure Lighthouse is properly configured. The package will automatically register its schema files.
ធានាថា Lighthouse ត្រូវបានកំណត់ត្រឹមត្រូវ។ Package នឹងចុះឈ្មោះ schema files ដោយស្វ័យប្រវត្តិ។

### Step 8: Configure Audit Logging (Optional) / ជំហានទី ៨: កំណត់ Audit Logging (ជម្រើស)

Add audit log channel to `config/logging.php`:
បន្ថែម audit log channel ទៅ `config/logging.php`:

```php
'channels' => [
    'audit' => [
        'driver' => 'single',
        'path' => storage_path('logs/audit.log'),
        'level' => 'info',
    ],
],
```

## Verification / ការផ្ទៀងផ្ទាត់

After installation, verify the package is working:
បន្ទាប់ពីដំឡើង, ផ្ទៀងផ្ទាត់ថា package ដំណើរការ:

1. Check that the service provider is registered:
ពិនិត្យមើលថា service provider ត្រូវបានចុះឈ្មោះ:
```bash
php artisan package:discover
```

2. Test GraphQL endpoint:
សាកល្បង GraphQL endpoint:
```graphql
query {
  me {
    id
    email
  }
}
```

## Next Steps / ជំហានបន្ត

- Read [Configuration Guide](./02-configuration.md) / អាន [មគ្គុទេសក៍ការកំណត់](./02-configuration.md)
- Read [Authentication Guide](./03-authentication.md) / អាន [មគ្គុទេសក៍ Authentication](./03-authentication.md)
- Read [Permissions & Roles Guide](./04-permissions-roles.md) / អាន [មគ្គុទេសក៍ Permissions & Roles](./04-permissions-roles.md)

