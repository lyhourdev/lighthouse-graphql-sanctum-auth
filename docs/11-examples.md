# Examples / ឧទាហរណ៍

Complete examples for common use cases.
ឧទាហរណ៍ពេញលេញសម្រាប់ use cases ទូទៅ។

## Complete User Model / User Model ពេញលេញ

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
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'tenant_id',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
```

## Complete Post Model with All Features

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Traits\{
    HasTenant,
    HasOwnership,
    HasAuditLog
};

class Post extends Model
{
    use HasTenant, HasOwnership, HasAuditLog;
    
    protected $fillable = [
        'title',
        'content',
        'user_id',
        'tenant_id',
    ];
    
    // Customize owner key if needed
    protected $ownerKey = 'user_id';
}
```

## Complete GraphQL Schema

```graphql
type User {
  id: ID!
  name: String!
  email: String!
  roles: [Role!]!
  permissions: [Permission!]!
  devices: [Device!]!
}

type Post {
  id: ID!
  title: String!
  content: String!
  user: User!
  created_at: DateTime!
  updated_at: DateTime!
}

type Query {
  # Authentication
  me: User @auth
  
  # Posts with tenant isolation
  posts: [Post!]! @belongsToTenant
  
  # User's posts with ownership check
  myPosts: [Post!]!
    @auth
    @belongsToTenant
    @ownership(relation: "user_id")
  
  # Admin only
  allUsers: [User!]! @hasRole(role: "admin")
  
  # Permission required
  draftPosts: [Post!]! @hasPermission(permission: "view drafts")
}

type Mutation {
  # Create post
  createPost(input: PostInput!): Post!
    @auth
    @belongsToTenant
    @hasPermission(permission: "create posts")
    @audit(action: "create")
  
  # Update post with ownership check
  updatePost(id: ID!, input: PostInput!): Post!
    @auth
    @hasPermission(permission: "edit posts")
    @ownership(relation: "user_id")
    @audit(action: "update")
  
  # Delete post (admin only)
  deletePost(id: ID!): Boolean!
    @auth
    @hasRole(role: "admin")
    @audit(action: "delete")
}
```

## Complete Resolver Example

```php
<?php

namespace App\GraphQL\Mutations;

use App\Models\Post;
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers\{
    AuthHelper,
    GraphQLHelper,
    TenantHelper,
    ResponseHelper
};

final class CreatePost
{
    public function __invoke(mixed $_, array $args, $context)
    {
        // Require authentication
        GraphQLHelper::requireAuth();
        
        // Require permission
        GraphQLHelper::requirePermission('create posts');
        
        // Get authenticated user
        $user = AuthHelper::userOrFail();
        
        // Get tenant ID if multi-tenancy is enabled
        $tenantId = TenantHelper::currentTenantId();
        
        // Create post
        $post = Post::create([
            'title' => $args['input']['title'],
            'content' => $args['input']['content'],
            'user_id' => $user->id,
            'tenant_id' => $tenantId,
        ]);
        
        // Return success response
        return ResponseHelper::success(
            ['post' => $post],
            'Post created successfully'
        );
    }
}
```

## Complete Login Mutation

```php
<?php

namespace App\GraphQL\Mutations;

use LeapLyhour\LighthouseGraphQLSanctumAuth\Auth\Services\LoginService;
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers\TokenHelper;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class Login
{
    public function __construct(
        private readonly LoginService $loginService
    ) {}

    public function __invoke(mixed $_, array $args, GraphQLContext $context): array
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
        
        // Register device if device management is enabled
        if (config('lighthouse-sanctum-auth.devices.enabled', true)) {
            $tokenModel = TokenHelper::getCurrentToken();
            
            $user->registerDevice(
                name: $args['device_name'] ?? 'Unknown Device',
                tokenId: $tokenModel?->id,
                ipAddress: $request?->ip(),
                userAgent: $request?->userAgent()
            );
        }
        
        return $result;
    }
}
```

## Complete Service Example

```php
<?php

namespace App\Services;

use App\Models\Post;
use LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers\{
    AuthHelper,
    TenantHelper
};

final class PostService
{
    public function create(array $data): Post
    {
        $user = AuthHelper::userOrFail();
        $tenantId = TenantHelper::currentTenantId();
        
        return Post::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'user_id' => $user->id,
            'tenant_id' => $tenantId,
        ]);
    }
    
    public function update(Post $post, array $data): Post
    {
        // Check ownership
        if (! $post->isOwnedByCurrentUser()) {
            throw new \RuntimeException('You do not own this post.');
        }
        
        $post->update($data);
        
        return $post->fresh();
    }
    
    public function delete(Post $post): bool
    {
        // Check ownership
        if (! $post->isOwnedByCurrentUser()) {
            throw new \RuntimeException('You do not own this post.');
        }
        
        return $post->delete();
    }
    
    public function getForCurrentUser(): \Illuminate\Database\Eloquent\Collection
    {
        return Post::ownedByCurrentUser()
            ->forCurrentTenant()
            ->get();
    }
}
```

## Next Steps

- Read [API Reference](./12-api-reference.md)
- Read [Troubleshooting](./13-troubleshooting.md)

