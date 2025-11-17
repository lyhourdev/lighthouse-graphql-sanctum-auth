# Audit Logging Guide / មគ្គុទេសក៍ Audit Logging

Complete guide to audit logging functionality.
មគ្គុទេសក៍ពេញលេញសម្រាប់ audit logging functionality។

## Overview / ទិដ្ឋភាពទូទៅ

Audit logging automatically tracks model changes and user actions for compliance and security purposes.
Audit logging តាមដាន model changes និង user actions ដោយស្វ័យប្រវត្តិសម្រាប់ compliance និង security។

## Configuration

Enable audit logging in `config/lighthouse-sanctum-auth.php`:

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

## Setup

### Configure Log Channel

Add audit log channel to `config/logging.php`:

```php
'channels' => [
    'audit' => [
        'driver' => 'single',
        'path' => storage_path('logs/audit.log'),
        'level' => 'info',
    ],
],
```

### Add HasAuditLog Trait

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

### Run Migration

```bash
php artisan migrate
```

This creates the `audit_logs` table.

## Automatic Logging

The trait automatically logs the following events:

- **created** - When a model is created
- **updated** - When a model is updated
- **deleted** - When a model is deleted

### What Gets Logged

- User ID (if authenticated)
- Action performed
- Model type and ID
- IP address
- User agent
- Timestamp
- Model data (attributes/changes)

## Manual Logging

You can manually log audit events:

```php
$post->logAuditEvent('custom_action', ['key' => 'value']);
```

## Using @audit Directive

Log GraphQL field access:

```graphql
type Mutation {
  deletePost(id: ID!): Post! @audit(action: "delete")
  updatePost(id: ID!, input: PostInput!): Post! @audit(action: "update")
}
```

## Querying Audit Logs

### Using AuditLog Model

```php
use LeapLyhour\LighthouseGraphQLSanctumAuth\Models\AuditLog;

// Get logs for specific action
$createdLogs = AuditLog::forAction('created')->get();

// Get logs for specific user
$userLogs = AuditLog::forUser($userId)->get();

// Get logs for specific model
$postLogs = AuditLog::forModel(Post::class, $postId)->get();

// Get logs in date range
$logs = AuditLog::inDateRange($startDate, $endDate)->get();
```

### Using Relationships

```php
// Get audit logs for a post
$post = Post::find(1);
$logs = $post->auditLogs; // If relationship is defined
```

## GraphQL Integration

Create GraphQL queries for audit logs:

```graphql
type Query {
  auditLogs(
    action: String
    userId: ID
    modelType: String
    modelId: ID
    startDate: DateTime
    endDate: DateTime
  ): [AuditLog!]! @auth @hasPermission(permission: "view audit logs")
}

type AuditLog {
  id: ID!
  user_id: ID
  action: String!
  auditable_type: String
  auditable_id: ID
  ip_address: String
  user_agent: String
  data: JSON
  metadata: JSON
  created_at: DateTime!
}
```

## Best Practices

1. **Enable for sensitive models** - Log changes to important data
2. **Use descriptive actions** - Use clear action names
3. **Store relevant data** - Include necessary context
4. **Regular cleanup** - Archive old logs periodically
5. **Monitor log size** - Prevent log files from growing too large

## Security Considerations

1. **Protect audit logs** - Restrict access to audit logs
2. **Encrypt sensitive data** - Don't log passwords or sensitive information
3. **Regular backups** - Backup audit logs regularly
4. **Compliance** - Ensure logs meet compliance requirements

## Example Implementation

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Traits\HasAuditLog;

class Post extends Model
{
    use HasAuditLog;
    
    protected $fillable = ['title', 'content', 'user_id'];
    
    // Exclude sensitive fields from audit log
    protected $hidden = ['password'];
}
```

## Next Steps

- Read [Examples](./11-examples.md)
- Read [API Reference](./12-api-reference.md)

