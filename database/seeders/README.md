# Seeders / Seeders

This directory contains database seeders for the package.
Directory នេះមាន database seeders សម្រាប់ package។

## Available Seeders / Seeders ដែលមាន

### PermissionSeeder

Seeds default permissions and roles for the application.
បំពេញ permissions និង roles លំនាំដើមសម្រាប់ application។

**Permissions Created:** / **Permissions ដែលបានបង្កើត:**

#### User Management / ការគ្រប់គ្រង Users
- `view users` - View users list / មើលបញ្ជី users
- `create users` - Create new users / បង្កើត users ថ្មី
- `edit users` - Edit existing users / កែ users ដែលមាន
- `delete users` - Delete users / លុប users

#### Role Management / ការគ្រប់គ្រង Roles
- `view roles` - View roles list / មើលបញ្ជី roles
- `create roles` - Create new roles / បង្កើត roles ថ្មី
- `edit roles` - Edit existing roles / កែ roles ដែលមាន
- `delete roles` - Delete roles / លុប roles
- `assign roles` - Assign roles to users / ផ្តល់ roles ទៅ users
- `remove roles` - Remove roles from users / ដក roles ពី users

#### Permission Management / ការគ្រប់គ្រង Permissions
- `view permissions` - View permissions list / មើលបញ្ជី permissions
- `create permissions` - Create new permissions / បង្កើត permissions ថ្មី
- `edit permissions` - Edit existing permissions / កែ permissions ដែលមាន
- `delete permissions` - Delete permissions / លុប permissions
- `assign permissions` - Assign permissions to users/roles / ផ្តល់ permissions ទៅ users/roles
- `remove permissions` - Remove permissions from users/roles / ដក permissions ពី users/roles

#### Post Management (Example) / ការគ្រប់គ្រង Posts (ឧទាហរណ៍)
- `view posts` - View posts / មើល posts
- `create posts` - Create new posts / បង្កើត posts ថ្មី
- `edit posts` - Edit existing posts / កែ posts ដែលមាន
- `delete posts` - Delete posts / លុប posts
- `publish posts` - Publish posts / Publish posts

#### System Management / ការគ្រប់គ្រង System
- `view audit logs` - View audit logs / មើល audit logs
- `manage system` - Manage system settings / គ្រប់គ្រងការកំណត់ system
- `manage tenants` - Manage tenants (multi-tenancy) / គ្រប់គ្រង tenants (multi-tenancy)

**Roles Created:** / **Roles ដែលបានបង្កើត:**

1. **super-admin** - Has all permissions / មាន permissions ទាំងអស់
2. **admin** - Can manage users, roles, and permissions / អាចគ្រប់គ្រង users, roles, និង permissions
3. **moderator** - Can moderate content / អាច moderate content
4. **editor** - Can create and edit content / អាចបង្កើត និង edit content
5. **author** - Can create content / អាចបង្កើត content
6. **user** - Basic user with limited permissions / User ទូទៅជាមួយ permissions មានកំណត់

## Usage / ការប្រើ

### Run All Seeders / ប្រតិបត្តិ Seeders ទាំងអស់

```bash
php artisan db:seed --class="LeapLyhour\\LighthouseGraphQLSanctumAuth\\Database\\Seeders\\DatabaseSeeder"
```

### Run Permission Seeder Only / ប្រតិបត្តិ Permission Seeder តែមួយ

```bash
php artisan db:seed --class="LeapLyhour\\LighthouseGraphQLSanctumAuth\\Database\\Seeders\\PermissionSeeder"
```

### In Your Application's DatabaseSeeder / ក្នុង DatabaseSeeder របស់ Application

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            \LeapLyhour\LighthouseGraphQLSanctumAuth\Database\Seeders\PermissionSeeder::class,
        ]);
    }
}
```

## Customization / ការប្ដូរផ្ទាល់ខ្លួន

You can extend the `PermissionSeeder` class to add your own permissions and roles:

អ្នកអាចពង្រីក class `PermissionSeeder` ដើម្បីបន្ថែម permissions និង roles ផ្ទាល់ខ្លួន:

```php
<?php

namespace Database\Seeders;

use LeapLyhour\LighthouseGraphQLSanctumAuth\Database\Seeders\PermissionSeeder as BasePermissionSeeder;

class PermissionSeeder extends BasePermissionSeeder
{
    protected function createPermissions(): array
    {
        $permissions = parent::createPermissions();
        
        // Add your custom permissions
        // បន្ថែម permissions ផ្ទាល់ខ្លួន
        $permissions['custom permission'] = Permission::firstOrCreate(
            ['name' => 'custom permission', 'guard_name' => 'web'],
            ['name' => 'custom permission', 'guard_name' => 'web']
        );
        
        return $permissions;
    }
}
```

