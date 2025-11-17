# Configuration Guide / មគ្គុទេសក៍ការកំណត់

Complete configuration reference for Lighthouse GraphQL Sanctum Auth.
ឯកសារកំណត់ពេញលេញសម្រាប់ Lighthouse GraphQL Sanctum Auth។

## Configuration File / File កំណត់

The configuration file is located at `config/lighthouse-sanctum-auth.php` after publishing.
File កំណត់មាននៅ `config/lighthouse-sanctum-auth.php` បន្ទាប់ពី publish។

## Authentication Configuration / ការកំណត់ Authentication

```php
'auth' => [
    // Guard to use for authentication
    // Guard ដែលប្រើសម្រាប់ authentication
    'guard' => env('LIGHTHOUSE_AUTH_GUARD', 'sanctum'),
    
    // Token expiration in minutes (default: 30 days)
    // ពេលវេលាផុតកំណត់ token ជានាទី (លំនាំដើម: ៣០ ថ្ងៃ)
    'token_expiration' => env('LIGHTHOUSE_TOKEN_EXPIRATION', 60 * 24 * 30),
    
    // Refresh token expiration in minutes (default: 90 days)
    // ពេលវេលាផុតកំណត់ refresh token ជានាទី (លំនាំដើម: ៩០ ថ្ងៃ)
    'refresh_token_expiration' => env('LIGHTHOUSE_REFRESH_TOKEN_EXPIRATION', 60 * 24 * 90),
],
```

### Environment Variables / Environment Variables

```env
LIGHTHOUSE_AUTH_GUARD=sanctum
LIGHTHOUSE_TOKEN_EXPIRATION=43200
LIGHTHOUSE_REFRESH_TOKEN_EXPIRATION=129600
```

## Multi-Tenancy Configuration / ការកំណត់ Multi-Tenancy

```php
'tenancy' => [
    // Enable multi-tenancy
    // បើក multi-tenancy
    'enabled' => env('LIGHTHOUSE_TENANCY_ENABLED', false),
    
    // Tenant resolver method: domain, header, token
    // វិធីសាស្ត្រដោះស្រាយ tenant: domain, header, token
    'resolver' => env('LIGHTHOUSE_TENANT_RESOLVER', 'header'),
    
    // Header name for tenant identification
    // ឈ្មោះ header សម្រាប់កំណត់អត្តសញ្ញាណ tenant
    'header_name' => env('LIGHTHOUSE_TENANT_HEADER', 'X-Tenant-ID'),
    
    // Database strategy: single_db, multi_db
    // យុទ្ធសាស្ត្រ database: single_db, multi_db
    'database_strategy' => env('LIGHTHOUSE_TENANT_DB_STRATEGY', 'single_db'),
],
```

### Environment Variables / Environment Variables

```env
LIGHTHOUSE_TENANCY_ENABLED=true
LIGHTHOUSE_TENANT_RESOLVER=header
LIGHTHOUSE_TENANT_HEADER=X-Tenant-ID
LIGHTHOUSE_TENANT_DB_STRATEGY=single_db
```

### Tenant Resolver Methods / វិធីសាស្ត្រដោះស្រាយ Tenant

1. **header** - Resolve tenant from HTTP header (default) / ដោះស្រាយ tenant ពី HTTP header (លំនាំដើម)
2. **domain** - Resolve tenant from subdomain / ដោះស្រាយ tenant ពី subdomain
3. **token** - Resolve tenant from authenticated user's token / ដោះស្រាយ tenant ពី token របស់ user ដែលបាន authenticate

## Two-Factor Authentication (2FA) Configuration / ការកំណត់ Two-Factor Authentication (2FA)

```php
'two_factor' => [
    // Enable 2FA
    // បើក 2FA
    'enabled' => env('LIGHTHOUSE_2FA_ENABLED', false),
    
    // Issuer name for QR code generation
    // ឈ្មោះ issuer សម្រាប់ការបង្កើត QR code
    'issuer' => env('LIGHTHOUSE_2FA_ISSUER', config('app.name')),
],
```

### Environment Variables / Environment Variables

```env
LIGHTHOUSE_2FA_ENABLED=true
LIGHTHOUSE_2FA_ISSUER="My Application"
```

## Device Management Configuration / ការកំណត់ការគ្រប់គ្រង Device

```php
'devices' => [
    // Enable device management
    // បើកការគ្រប់គ្រង device
    'enabled' => env('LIGHTHOUSE_DEVICES_ENABLED', true),
    
    // Maximum devices per user
    // ចំនួន device អតិបរមាក្នុងមួយ user
    'max_per_user' => env('LIGHTHOUSE_DEVICES_MAX_PER_USER', 10),
],
```

### Environment Variables / Environment Variables

```env
LIGHTHOUSE_DEVICES_ENABLED=true
LIGHTHOUSE_DEVICES_MAX_PER_USER=10
```

## Audit Logging Configuration / ការកំណត់ Audit Logging

```php
'audit' => [
    // Enable audit logging
    // បើក audit logging
    'enabled' => env('LIGHTHOUSE_AUDIT_ENABLED', true),
    
    // Log model events
    // កត់ត្រា model events
    'log_events' => [
        'created' => true,
        'updated' => true,
        'deleted' => true,
    ],
],
```

### Environment Variables / Environment Variables

```env
LIGHTHOUSE_AUDIT_ENABLED=true
```

## IP Filtering Configuration / ការកំណត់ការច្រោះ IP

```php
'ip_filtering' => [
    // Enable IP filtering
    // បើកការច្រោះ IP
    'enabled' => env('LIGHTHOUSE_IP_FILTERING_ENABLED', false),
    
    // Allowed IP addresses (comma-separated, CIDR notation supported)
    // IP addresses ដែលអនុញ្ញាត (ដាច់ដោយ comma, គាំទ្រ CIDR notation)
    'allowed_ips' => env('LIGHTHOUSE_ALLOWED_IPS', ''),
],
```

### Environment Variables / Environment Variables

```env
LIGHTHOUSE_IP_FILTERING_ENABLED=true
LIGHTHOUSE_ALLOWED_IPS=192.168.1.0/24,10.0.0.1
```

## Complete Configuration Example / ឧទាហរណ៍ការកំណត់ពេញលេញ

```php
<?php

return [
    'auth' => [
        'guard' => 'sanctum',
        'token_expiration' => 43200, // 30 days / ៣០ ថ្ងៃ
        'refresh_token_expiration' => 129600, // 90 days / ៩០ ថ្ងៃ
    ],
    
    'tenancy' => [
        'enabled' => true,
        'resolver' => 'header',
        'header_name' => 'X-Tenant-ID',
        'database_strategy' => 'single_db',
    ],
    
    'two_factor' => [
        'enabled' => false,
        'issuer' => 'My Application',
    ],
    
    'devices' => [
        'enabled' => true,
        'max_per_user' => 10,
    ],
    
    'audit' => [
        'enabled' => true,
        'log_events' => [
            'created' => true,
            'updated' => true,
            'deleted' => true,
        ],
    ],
    
    'ip_filtering' => [
        'enabled' => false,
        'allowed_ips' => '',
    ],
];
```

## Next Steps / ជំហានបន្ត

- Read [Authentication Guide](./03-authentication.md) / អាន [មគ្គុទេសក៍ Authentication](./03-authentication.md)
- Read [Multi-Tenancy Guide](./06-multi-tenancy.md) / អាន [មគ្គុទេសក៍ Multi-Tenancy](./06-multi-tenancy.md)

