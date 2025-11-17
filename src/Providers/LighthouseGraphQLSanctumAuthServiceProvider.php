<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\Providers;

use Illuminate\Support\ServiceProvider;
use Nuwave\Lighthouse\Events\RegisterDirectiveNamespaces;

/**
 * Service Provider for Lighthouse GraphQL Sanctum Auth Package
 * 
 * សេវាផ្តល់សម្រាប់ package Lighthouse GraphQL Sanctum Auth
 * 
 * This provider registers all package services, directives, and configurations.
 * សេវានេះចុះឈ្មោះ services, directives, និង configurations ទាំងអស់នៃ package។
 */
final class LighthouseGraphQLSanctumAuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * ចុះឈ្មោះ services ណាមួយនៃ application។
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/lighthouse-sanctum-auth.php',
            'lighthouse-sanctum-auth'
        );

        $this->registerServices();
    }

    /**
     * Bootstrap any application services.
     * ចាប់ផ្តើម services ណាមួយនៃ application។
     */
    public function boot(): void
    {
        $this->publishConfig();
        $this->publishMigrations();
        $this->loadGraphQLSchemas();
        $this->registerDirectiveNamespace();
    }

    /**
     * Register custom GraphQL directives namespace.
     * ចុះឈ្មោះ namespace នៃ GraphQL directives ផ្ទាល់ខ្លួន។
     */
    private function registerDirectiveNamespace(): void
    {
        $this->app['events']->listen(
            RegisterDirectiveNamespaces::class,
            fn (): string => 'LeapLyhour\\LighthouseGraphQLSanctumAuth\\GraphQL\\Directives'
        );
    }

    /**
     * Register package services.
     * ចុះឈ្មោះ services នៃ package។
     */
    private function registerServices(): void
    {
        $this->app->singleton(
            \LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Tenancy\TenantResolver::class
        );

        $this->app->singleton(
            \LeapLyhour\LighthouseGraphQLSanctumAuth\Support\AuditLogger::class
        );
    }

    /**
     * Publish configuration files.
     * បោះពុម្ព configuration files។
     */
    private function publishConfig(): void
    {
        $this->publishes([
            __DIR__.'/../../config/lighthouse-sanctum-auth.php' => config_path('lighthouse-sanctum-auth.php'),
        ], 'lighthouse-sanctum-auth-config');
    }

    /**
     * Publish migration files.
     * បោះពុម្ព migration files។
     */
    private function publishMigrations(): void
    {
        $this->publishes([
            __DIR__.'/../../database/migrations' => database_path('migrations'),
        ], 'lighthouse-sanctum-auth-migrations');
    }

    /**
     * Load GraphQL schema files.
     * ផ្ទុក GraphQL schema files។
     */
    private function loadGraphQLSchemas(): void
    {
        // Lighthouse v6 uses a single schema_path or array of paths
        // Lighthouse v6 ប្រើ schema_path តែមួយ ឬ array នៃ paths
        $currentSchemaPath = config('lighthouse.schema_path', base_path('graphql/schema.graphql'));
        
        // Convert to array if it's a string
        // បម្លែងទៅ array ប្រសិនបើវាជា string
        $schemaPaths = is_array($currentSchemaPath) ? $currentSchemaPath : [$currentSchemaPath];
        
        // Add our package schema directory
        // បន្ថែម schema directory នៃ package របស់យើង
        $packageSchemaPath = __DIR__.'/../GraphQL/Schema';
        
        if (is_dir($packageSchemaPath)) {
            $schemaPaths[] = $packageSchemaPath;
            $this->app['config']->set('lighthouse.schema_path', $schemaPaths);
        }
    }
}

