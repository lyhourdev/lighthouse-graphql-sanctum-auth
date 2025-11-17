<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Authentication Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Sanctum authentication settings.
    | កំណត់សម្រាប់ការកំណត់ authentication Sanctum។
    |
    */

    'auth' => [
        /*
         * The guard to use for authentication.
         * Guard ដែលត្រូវប្រើសម្រាប់ authentication។
         */
        'guard' => env('LIGHTHOUSE_AUTH_GUARD', 'sanctum'),

        /*
         * Token expiration time in minutes.
         * ពេលវេលាផុតកំណត់ token ជានាទី។
         */
        'token_expiration' => env('LIGHTHOUSE_TOKEN_EXPIRATION', 60 * 24 * 30), // 30 days

        /*
         * Refresh token expiration time in minutes.
         * ពេលវេលាផុតកំណត់ refresh token ជានាទី។
         */
        'refresh_token_expiration' => env('LIGHTHOUSE_REFRESH_TOKEN_EXPIRATION', 60 * 24 * 90), // 90 days
    ],

    /*
    |--------------------------------------------------------------------------
    | Multi-Tenancy Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for multi-tenant support.
    | កំណត់សម្រាប់ការគាំទ្រ multi-tenant។
    |
    */

    'tenancy' => [
        /*
         * Enable multi-tenancy.
         * បើក multi-tenancy។
         */
        'enabled' => env('LIGHTHOUSE_TENANCY_ENABLED', false),

        /*
         * Tenant resolver methods (domain, header, token).
         * វិធីសាស្ត្រដោះស្រាយ tenant (domain, header, token)។
         */
        'resolver' => env('LIGHTHOUSE_TENANT_RESOLVER', 'header'), // domain, header, token

        /*
         * Header name for tenant identification.
         * ឈ្មោះ header សម្រាប់កំណត់អត្តសញ្ញាណ tenant។
         */
        'header_name' => env('LIGHTHOUSE_TENANT_HEADER', 'X-Tenant-ID'),

        /*
         * Database connection strategy (single_db, multi_db).
         * យុទ្ធសាស្ត្រ database connection (single_db, multi_db)។
         */
        'database_strategy' => env('LIGHTHOUSE_TENANT_DB_STRATEGY', 'single_db'), // single_db, multi_db
    ],

    /*
    |--------------------------------------------------------------------------
    | Two-Factor Authentication (2FA) Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for 2FA using Google Authenticator.
    | កំណត់សម្រាប់ 2FA ដោយប្រើ Google Authenticator។
    |
    */

    'two_factor' => [
        /*
         * Enable 2FA.
         * បើក 2FA។
         */
        'enabled' => env('LIGHTHOUSE_2FA_ENABLED', false),

        /*
         * Issuer name for QR code generation.
         * ឈ្មោះ issuer សម្រាប់ការបង្កើត QR code។
         */
        'issuer' => env('LIGHTHOUSE_2FA_ISSUER', config('app.name')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Device Management Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for device management.
    | កំណត់សម្រាប់ការគ្រប់គ្រង device។
    |
    */

    'devices' => [
        /*
         * Enable device management.
         * បើកការគ្រប់គ្រង device។
         */
        'enabled' => env('LIGHTHOUSE_DEVICES_ENABLED', true),

        /*
         * Maximum number of devices per user.
         * ចំនួន device អតិបរមាក្នុងមួយ user។
         */
        'max_per_user' => env('LIGHTHOUSE_DEVICES_MAX_PER_USER', 10),
    ],

    /*
    |--------------------------------------------------------------------------
    | Audit Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for audit logging system.
    | កំណត់សម្រាប់ប្រព័ន្ធ audit logging។
    |
    */

    'audit' => [
        /*
         * Enable audit logging.
         * បើក audit logging។
         */
        'enabled' => env('LIGHTHOUSE_AUDIT_ENABLED', true),

        /*
         * Log model events (created, updated, deleted).
         * កត់ត្រា model events (created, updated, deleted)។
         */
        'log_events' => [
            'created' => true,
            'updated' => true,
            'deleted' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IP Filtering Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for IP filtering.
    | កំណត់សម្រាប់ការច្រោះ IP។
    |
    */

    'ip_filtering' => [
        /*
         * Enable IP filtering.
         * បើកការច្រោះ IP។
         */
        'enabled' => env('LIGHTHOUSE_IP_FILTERING_ENABLED', false),

        /*
         * Allowed IP addresses (CIDR notation supported).
         * IP addresses ដែលអនុញ្ញាត (គាំទ្រ CIDR notation)។
         */
        'allowed_ips' => env('LIGHTHOUSE_ALLOWED_IPS', ''),
    ],
];

