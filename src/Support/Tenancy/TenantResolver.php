<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Tenancy;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Tenant Resolver
 * 
 * Resolves the current tenant from various sources (domain, header, token).
 * ដោះស្រាយ tenant បច្ចុប្បន្នពីប្រភពផ្សេងៗ (domain, header, token)។
 */
final class TenantResolver
{
    /**
     * Resolve tenant ID from the request.
     * ដោះស្រាយ tenant ID ពី request។
     */
    public function resolve(?Request $request): ?string
    {
        if ($request === null) {
            return null;
        }

        $resolverMethod = config('lighthouse-sanctum-auth.tenancy.resolver', 'header');

        return match ($resolverMethod) {
            'domain' => $this->resolveFromDomain($request),
            'header' => $this->resolveFromHeader($request),
            'token' => $this->resolveFromToken($request),
            default => null,
        };
    }

    /**
     * Resolve tenant from domain.
     * ដោះស្រាយ tenant ពី domain។
     */
    private function resolveFromDomain(Request $request): ?string
    {
        $host = $request->getHost();
        $parts = explode('.', $host);

        // Assuming subdomain is the tenant identifier
        // សន្មត់ថា subdomain គឺជា tenant identifier
        if (count($parts) > 2) {
            return $parts[0];
        }

        return null;
    }

    /**
     * Resolve tenant from header.
     * ដោះស្រាយ tenant ពី header។
     */
    private function resolveFromHeader(Request $request): ?string
    {
        $headerName = config('lighthouse-sanctum-auth.tenancy.header_name', 'X-Tenant-ID');

        return $request->header($headerName);
    }

    /**
     * Resolve tenant from authenticated user's token.
     * ដោះស្រាយ tenant ពី token របស់ user ដែលបាន authenticate។
     */
    private function resolveFromToken(Request $request): ?string
    {
        $user = Auth::user();

        if ($user === null) {
            return null;
        }

        // Assuming user model has tenant_id attribute
        // សន្មត់ថា user model មាន tenant_id attribute
        if (method_exists($user, 'getTenantId')) {
            return (string) $user->getTenantId();
        }

        return $user->getAttribute('tenant_id');
    }
}

