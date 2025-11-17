<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\GraphQL\Mutations;

use LeapLyhour\LighthouseGraphQLSanctumAuth\Auth\Services\RefreshTokenService;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * Refresh Token Mutation
 * 
 * Refreshes an access token using a refresh token.
 * ធ្វើឱ្យ access token ថ្មីដោយប្រើ refresh token។
 */
final class RefreshToken
{
    public function __construct(
        private readonly RefreshTokenService $refreshTokenService,
    ) {}

    /**
     * Resolve the refresh token mutation.
     * ដោះស្រាយ refresh token mutation។
     * 
     * @param mixed $_
     * @param array<string, mixed> $args
     * @return array<string, mixed>
     */
    public function __invoke(mixed $_, array $args, GraphQLContext $context): array
    {
        return $this->refreshTokenService->refresh($args['refresh_token']);
    }
}

