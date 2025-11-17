<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Database Seeder
 *
 * Main seeder that calls all other seeders.
 * Seeder សំខាន់ដែលហៅ seeders ផ្សេងៗទាំងអស់។
 */
final class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * ប្រតិបត្តិ database seeds។
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
        ]);
    }
}
