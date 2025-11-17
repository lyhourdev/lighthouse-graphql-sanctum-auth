<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\GraphQL\Mutations;

use LeapLyhour\LighthouseGraphQLSanctumAuth\Auth\Services\LoginService;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * Login Mutation
 * 
 * Authenticates a user and returns an access token.
 * Authenticate user និងត្រឡប់ access token។
 */
final class Login
{
    public function __construct(
        private readonly LoginService $loginService,
    ) {}

    /**
     * Resolve the login mutation.
     * ដោះស្រាយ login mutation។
     * 
     * @param mixed $_
     * @param array<string, mixed> $args
     * @return array<string, mixed>
     */
    public function __invoke(mixed $_, array $args, GraphQLContext $context): array
    {
        $request = $context->request();

        return $this->loginService->login(
            credentials: [
                'email' => $args['email'],
                'password' => $args['password'],
            ],
            deviceName: $args['device_name'] ?? 'unknown',
            ipAddress: $request?->ip(),
            userAgent: $request?->userAgent(),
        );
    }
}

