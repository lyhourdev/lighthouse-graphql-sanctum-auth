# Troubleshooting / ការដោះស្រាយបញ្ហា

Common issues and solutions.
បញ្ហាទូទៅ និងដំណោះស្រាយ។

## Authentication Issues / បញ្ហា Authentication

### "You must be authenticated to access this field" / "អ្នកត្រូវតែ authenticate ដើម្បីចូលប្រើ field នេះ"

**Problem:** User is not authenticated. / **បញ្ហា:** User មិនបាន authenticate។

**Solution:** / **ដំណោះស្រាយ:**
- Ensure token is included in `Authorization` header / ធានាថា token ត្រូវបានរួមបញ្ចូលក្នុង header `Authorization`
- Check token is valid and not expired / ពិនិត្យមើលថា token ត្រឹមត្រូវ និងមិនផុតកំណត់
- Verify Sanctum is properly configured / ផ្ទៀងផ្ទាត់ថា Sanctum ត្រូវបានកំណត់ត្រឹមត្រូវ

### "The provided credentials are incorrect"

**Problem:** Invalid login credentials.

**Solution:**
- Verify email and password are correct
- Check user exists in database
- Ensure password is properly hashed

## Permission Issues

### "You must have the 'X' permission to access this field"

**Problem:** User doesn't have required permission.

**Solution:**
- Assign permission to user or role
- Verify permission exists
- Check user's roles have the permission

### "User model must use Spatie Permission trait"

**Problem:** User model missing HasRoles trait.

**Solution:**
```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
}
```

## Multi-Tenancy Issues

### "Tenant could not be resolved"

**Problem:** Tenant resolver cannot find tenant.

**Solution:**
- Check multi-tenancy is enabled
- Verify resolver method is correct
- Ensure header/domain/token contains tenant ID
- Check user has tenant_id attribute

### Data leaking between tenants

**Problem:** Tenant isolation not working.

**Solution:**
- Use `@belongsToTenant` directive
- Use `HasTenant` trait on models
- Verify `tenant_id` is set on records
- Check scopes are applied correctly

## Device Management Issues

### Devices not being registered

**Problem:** Device registration failing.

**Solution:**
- Check device management is enabled
- Verify migration has been run
- Ensure `HasDevices` trait is on User model
- Check device limit configuration

## Audit Logging Issues

### Audit logs not being created

**Problem:** Audit logging not working.

**Solution:**
- Check audit logging is enabled
- Verify `HasAuditLog` trait is on model
- Check log channel is configured
- Ensure migration has been run

## Directive Issues

### Directives not working

**Problem:** Custom directives not recognized.

**Solution:**
- Clear Lighthouse schema cache: `php artisan lighthouse:clear-cache`
- Verify service provider is registered
- Check directive namespace is correct
- Ensure directives are in correct namespace

## Token Issues

### Token expired

**Problem:** Token has expired.

**Solution:**
- Use refresh token to get new token
- Check token expiration configuration
- Implement token refresh before expiration

### Token invalid

**Problem:** Token is not valid.

**Solution:**
- Verify token format is correct
- Check token exists in database
- Ensure token hasn't been revoked

## General Issues

### Package not discovered

**Problem:** Service provider not registered.

**Solution:**
```bash
php artisan package:discover
composer dump-autoload
```

### Schema not loading

**Problem:** GraphQL schema not found.

**Solution:**
- Check schema path in Lighthouse config
- Verify schema files exist
- Clear schema cache

### Migration errors

**Problem:** Migrations failing.

**Solution:**
- Check database connection
- Verify table doesn't already exist
- Check migration file syntax
- Run migrations individually

## Getting Help

If you encounter issues not covered here:

1. Check the [Examples](./11-examples.md)
2. Review the [API Reference](./12-api-reference.md)
3. Check Laravel and Lighthouse documentation
4. Review package logs

## Common Configuration Mistakes

1. **Missing traits on User model** - Ensure all required traits are added
2. **Incorrect guard configuration** - Verify guard matches configuration
3. **Missing migrations** - Run all package migrations
4. **Incorrect schema path** - Check Lighthouse schema configuration
5. **Missing log channel** - Configure audit log channel

