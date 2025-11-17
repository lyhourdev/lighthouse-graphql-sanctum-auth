<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Helpers;

/**
 * Response Helper
 * 
 * Helper functions for creating standardized GraphQL responses.
 * មុខងារជំនួយសម្រាប់ការបង្កើត responses GraphQL ស្តង់ដារ។
 */
final class ResponseHelper
{
    /**
     * Create a success response.
     * បង្កើត success response។
     * 
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public static function success(array $data = [], ?string $message = null): array
    {
        $response = [
            'success' => true,
            'data' => $data,
        ];

        if ($message !== null) {
            $response['message'] = $message;
        }

        return $response;
    }

    /**
     * Create an error response.
     * បង្កើត error response។
     * 
     * @return array<string, mixed>
     */
    public static function error(string $message, ?string $code = null, array $data = []): array
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($code !== null) {
            $response['code'] = $code;
        }

        if (! empty($data)) {
            $response['data'] = $data;
        }

        return $response;
    }

    /**
     * Create a paginated response.
     * បង្កើត paginated response។
     * 
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public static function paginated(array $data, int $total, int $page, int $perPage): array
    {
        return [
            'data' => $data,
            'pagination' => [
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'last_page' => (int) ceil($total / $perPage),
            ],
        ];
    }
}

