<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Permission Seeder
 *
 * Seeds default permissions and roles for the application.
 * បំពេញ permissions និង roles លំនាំដើមសម្រាប់ application។
 */
final class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * ប្រតិបត្តិ database seeds។
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        // កំណត់ roles និង permissions cached ឡើងវិញ
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        // បង្កើត permissions
        $permissions = $this->createPermissions();

        // Create roles
        // បង្កើត roles
        $roles = $this->createRoles();

        // Assign permissions to roles
        // ផ្តល់ permissions ទៅ roles
        $this->assignPermissionsToRoles($permissions, $roles);
    }

    /**
     * Create all permissions.
     * បង្កើត permissions ទាំងអស់។
     *
     * @return array<string, Permission>
     */
    private function createPermissions(): array
    {
        $permissions = [];

        // User management permissions
        // Permissions ការគ្រប់គ្រង users
        $permissions['view users'] = Permission::firstOrCreate(
            ['name' => 'view users', 'guard_name' => 'web'],
            ['name' => 'view users', 'guard_name' => 'web']
        );

        $permissions['create users'] = Permission::firstOrCreate(
            ['name' => 'create users', 'guard_name' => 'web'],
            ['name' => 'create users', 'guard_name' => 'web']
        );

        $permissions['edit users'] = Permission::firstOrCreate(
            ['name' => 'edit users', 'guard_name' => 'web'],
            ['name' => 'edit users', 'guard_name' => 'web']
        );

        $permissions['delete users'] = Permission::firstOrCreate(
            ['name' => 'delete users', 'guard_name' => 'web'],
            ['name' => 'delete users', 'guard_name' => 'web']
        );

        // Role management permissions
        // Permissions ការគ្រប់គ្រង roles
        $permissions['view roles'] = Permission::firstOrCreate(
            ['name' => 'view roles', 'guard_name' => 'web'],
            ['name' => 'view roles', 'guard_name' => 'web']
        );

        $permissions['create roles'] = Permission::firstOrCreate(
            ['name' => 'create roles', 'guard_name' => 'web'],
            ['name' => 'create roles', 'guard_name' => 'web']
        );

        $permissions['edit roles'] = Permission::firstOrCreate(
            ['name' => 'edit roles', 'guard_name' => 'web'],
            ['name' => 'edit roles', 'guard_name' => 'web']
        );

        $permissions['delete roles'] = Permission::firstOrCreate(
            ['name' => 'delete roles', 'guard_name' => 'web'],
            ['name' => 'delete roles', 'guard_name' => 'web']
        );

        $permissions['assign roles'] = Permission::firstOrCreate(
            ['name' => 'assign roles', 'guard_name' => 'web'],
            ['name' => 'assign roles', 'guard_name' => 'web']
        );

        $permissions['remove roles'] = Permission::firstOrCreate(
            ['name' => 'remove roles', 'guard_name' => 'web'],
            ['name' => 'remove roles', 'guard_name' => 'web']
        );

        // Permission management permissions
        // Permissions ការគ្រប់គ្រង permissions
        $permissions['view permissions'] = Permission::firstOrCreate(
            ['name' => 'view permissions', 'guard_name' => 'web'],
            ['name' => 'view permissions', 'guard_name' => 'web']
        );

        $permissions['create permissions'] = Permission::firstOrCreate(
            ['name' => 'create permissions', 'guard_name' => 'web'],
            ['name' => 'create permissions', 'guard_name' => 'web']
        );

        $permissions['edit permissions'] = Permission::firstOrCreate(
            ['name' => 'edit permissions', 'guard_name' => 'web'],
            ['name' => 'edit permissions', 'guard_name' => 'web']
        );

        $permissions['delete permissions'] = Permission::firstOrCreate(
            ['name' => 'delete permissions', 'guard_name' => 'web'],
            ['name' => 'delete permissions', 'guard_name' => 'web']
        );

        $permissions['assign permissions'] = Permission::firstOrCreate(
            ['name' => 'assign permissions', 'guard_name' => 'web'],
            ['name' => 'assign permissions', 'guard_name' => 'web']
        );

        $permissions['remove permissions'] = Permission::firstOrCreate(
            ['name' => 'remove permissions', 'guard_name' => 'web'],
            ['name' => 'remove permissions', 'guard_name' => 'web']
        );

        // Post management permissions (example)
        // Permissions ការគ្រប់គ្រង posts (ឧទាហរណ៍)
        $permissions['view posts'] = Permission::firstOrCreate(
            ['name' => 'view posts', 'guard_name' => 'web'],
            ['name' => 'view posts', 'guard_name' => 'web']
        );

        $permissions['create posts'] = Permission::firstOrCreate(
            ['name' => 'create posts', 'guard_name' => 'web'],
            ['name' => 'create posts', 'guard_name' => 'web']
        );

        $permissions['edit posts'] = Permission::firstOrCreate(
            ['name' => 'edit posts', 'guard_name' => 'web'],
            ['name' => 'edit posts', 'guard_name' => 'web']
        );

        $permissions['delete posts'] = Permission::firstOrCreate(
            ['name' => 'delete posts', 'guard_name' => 'web'],
            ['name' => 'delete posts', 'guard_name' => 'web']
        );

        $permissions['publish posts'] = Permission::firstOrCreate(
            ['name' => 'publish posts', 'guard_name' => 'web'],
            ['name' => 'publish posts', 'guard_name' => 'web']
        );

        // Audit and system permissions
        // Permissions audit និង system
        $permissions['view audit logs'] = Permission::firstOrCreate(
            ['name' => 'view audit logs', 'guard_name' => 'web'],
            ['name' => 'view audit logs', 'guard_name' => 'web']
        );

        $permissions['manage system'] = Permission::firstOrCreate(
            ['name' => 'manage system', 'guard_name' => 'web'],
            ['name' => 'manage system', 'guard_name' => 'web']
        );

        $permissions['manage tenants'] = Permission::firstOrCreate(
            ['name' => 'manage tenants', 'guard_name' => 'web'],
            ['name' => 'manage tenants', 'guard_name' => 'web']
        );

        return $permissions;
    }

    /**
     * Create all roles.
     * បង្កើត roles ទាំងអស់។
     *
     * @return array<string, Role>
     */
    private function createRoles(): array
    {
        $roles = [];

        // Super Admin - Has all permissions
        // Super Admin - មាន permissions ទាំងអស់
        $roles['super-admin'] = Role::firstOrCreate(
            ['name' => 'super-admin', 'guard_name' => 'web'],
            ['name' => 'super-admin', 'guard_name' => 'web']
        );

        // Admin - Can manage users, roles, and permissions
        // Admin - អាចគ្រប់គ្រង users, roles, និង permissions
        $roles['admin'] = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web'],
            ['name' => 'admin', 'guard_name' => 'web']
        );

        // Moderator - Can moderate content
        // Moderator - អាច moderate content
        $roles['moderator'] = Role::firstOrCreate(
            ['name' => 'moderator', 'guard_name' => 'web'],
            ['name' => 'moderator', 'guard_name' => 'web']
        );

        // Editor - Can create and edit content
        // Editor - អាចបង្កើត និង edit content
        $roles['editor'] = Role::firstOrCreate(
            ['name' => 'editor', 'guard_name' => 'web'],
            ['name' => 'editor', 'guard_name' => 'web']
        );

        // Author - Can create content
        // Author - អាចបង្កើត content
        $roles['author'] = Role::firstOrCreate(
            ['name' => 'author', 'guard_name' => 'web'],
            ['name' => 'author', 'guard_name' => 'web']
        );

        // User - Basic user with limited permissions
        // User - User ទូទៅជាមួយ permissions មានកំណត់
        $roles['user'] = Role::firstOrCreate(
            ['name' => 'user', 'guard_name' => 'web'],
            ['name' => 'user', 'guard_name' => 'web']
        );

        return $roles;
    }

    /**
     * Assign permissions to roles.
     * ផ្តល់ permissions ទៅ roles។
     *
     * @param  array<string, Permission>  $permissions
     * @param  array<string, Role>  $roles
     */
    private function assignPermissionsToRoles(array $permissions, array $roles): void
    {
        // Super Admin gets all permissions
        // Super Admin ទទួល permissions ទាំងអស់
        $roles['super-admin']->givePermissionTo(Permission::all());

        // Admin permissions
        // Permissions Admin
        $roles['admin']->givePermissionTo([
            $permissions['view users'],
            $permissions['create users'],
            $permissions['edit users'],
            $permissions['delete users'],
            $permissions['view roles'],
            $permissions['create roles'],
            $permissions['edit roles'],
            $permissions['delete roles'],
            $permissions['assign roles'],
            $permissions['remove roles'],
            $permissions['view permissions'],
            $permissions['create permissions'],
            $permissions['edit permissions'],
            $permissions['delete permissions'],
            $permissions['assign permissions'],
            $permissions['remove permissions'],
            $permissions['view posts'],
            $permissions['create posts'],
            $permissions['edit posts'],
            $permissions['delete posts'],
            $permissions['publish posts'],
            $permissions['view audit logs'],
        ]);

        // Moderator permissions
        // Permissions Moderator
        $roles['moderator']->givePermissionTo([
            $permissions['view users'],
            $permissions['view posts'],
            $permissions['edit posts'],
            $permissions['delete posts'],
            $permissions['publish posts'],
        ]);

        // Editor permissions
        // Permissions Editor
        $roles['editor']->givePermissionTo([
            $permissions['view posts'],
            $permissions['create posts'],
            $permissions['edit posts'],
            $permissions['publish posts'],
        ]);

        // Author permissions
        // Permissions Author
        $roles['author']->givePermissionTo([
            $permissions['view posts'],
            $permissions['create posts'],
            $permissions['edit posts'],
        ]);

        // User permissions (basic)
        // Permissions User (មូលដ្ឋាន)
        $roles['user']->givePermissionTo([
            $permissions['view posts'],
        ]);
    }
}
