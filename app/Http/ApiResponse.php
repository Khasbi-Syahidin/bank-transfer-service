<?php

namespace App\Http;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

class ApiResponse
{
    private const MAP = [
        // Error responses
        // 500 Internal Server Error
        'UNIVERSAL_ERROR' => ['success' => false, 'code' => 50001, 'message' => 'Internal server error, please try again later.'],
        'SERVER_ERROR' => ['success' => false, 'code' => 50002, 'message' => 'Server error, please try again later.'],

        // 400 Bad Request
        'INVALID_REQUEST' => ['success' => false, 'code' => 40001, 'message' => 'Invalid request'],

        // 404 Not Found
        'NOT_FOUND' => ['success' => false, 'code' => 40401, 'message' => 'Resource not found.'],

        // 401 Unauthorized
        'UNAUTHORIZED' => ['success' => false, 'code' => 40101, 'message' => 'Unauthorized'],

        // 429 Too Many Requests
        'TOO_MANY_REQUESTS' => ['success' => false, 'code' => 42901, 'message' => 'Too many requests'],

        // 422 Unprocessable Entity
        'VALIDATION_ERROR' => ['success' => false, 'code' => 42201, 'message' => 'The given data was invalid.'],

        // Success responses
        'SUCCESS' => ['success' => true, 'code' => 20000, 'message' => 'Success'],
        'SUCCESS_CREATED' => ['success' => true, 'code' => 20100, 'message' => 'Success'],
    ];

    private const DEFAULT_RESPONSE = self::MAP['UNIVERSAL_ERROR'];

    /**
     * Internal helper to get the base response structure.
     *
     * @param  array<string, mixed>  $additional
     * @return array{0: array<string, mixed>, 1: int}
     */
    protected static function getBaseResponse(
        string $key,
        ?string $message = null,
        array|JsonResource|MessageBag|ViewErrorBag $data = [],
        array $additional = []
    ): array {
        $body = self::MAP[$key] ?? self::DEFAULT_RESPONSE;
        $status = (int) substr((string) $body['code'], 0, 3);

        if ($message !== null) {
            $body['message'] = $message;
        }

        if (! empty($data)) {
            if ($data instanceof JsonResource) {
                $body['data'] = $data->response()->getData(true);
            } elseif ($data instanceof MessageBag || $data instanceof ViewErrorBag) {
                $body['errors'] = $data->toArray();
            } else {
                $body['data'] = $data;
            }
        }

        $body = array_merge($body, $additional);

        return [$body, $status];
    }

    /**
     * Generate a success JSON response.
     *
     * @param  array<string, mixed>  $additional
     */
    public static function success(string $message = 'Success', array|JsonResource $data = [], array $additional = []): JsonResponse
    {
        [$body, $status] = self::getBaseResponse('SUCCESS', $message, $data, $additional);

        return response()->json($body, $status);
    }

    /**
     * Generate a success (created) JSON response.
     *
     * @param  array<string, mixed>  $additional
     */
    public static function successCreated(string $message = 'Success', array|JsonResource $data = [], array $additional = []): JsonResponse
    {
        [$body, $status] = self::getBaseResponse('SUCCESS_CREATED', $message, $data, $additional);

        return response()->json($body, $status);
    }

    /**
     * Generate an error JSON response.
     *
     * @param  array<string, mixed>  $additional
     */
    public static function error(string $key = 'UNIVERSAL_ERROR', ?string $message = null, array $additional = []): JsonResponse
    {
        [$body, $status] = self::getBaseResponse($key, $message, [], $additional);

        return response()->json($body, $status);
    }

    /**
     * Generate a bad request (400) JSON response.
     *
     * @param  array<string, mixed>  $additional
     */
    public static function badRequest(?string $message = null, array $additional = []): JsonResponse
    {
        [$body, $status] = self::getBaseResponse('INVALID_REQUEST', $message, [], $additional);

        return response()->json($body, $status);
    }

    /**
     * Generate a not found (404) JSON response.
     *
     * @param  array<string, mixed>  $additional
     */
    public static function notFound(?string $message = null, array $additional = []): JsonResponse
    {
        [$body, $status] = self::getBaseResponse('NOT_FOUND', $message, [], $additional);

        return response()->json($body, $status);
    }

    /**
     * Generate an unauthorized (401) JSON response.
     *
     * @param  array<string, mixed>  $additional
     */
    public static function unauthorized(?string $message = null, array $additional = []): JsonResponse
    {
        [$body, $status] = self::getBaseResponse('UNAUTHORIZED', $message, [], $additional);

        return response()->json($body, $status);
    }

    /**
     * Generate a too many requests (429) JSON response.
     *
     * @param  array<string, mixed>  $additional
     */
    public static function tooManyRequests(?string $message = null, array $additional = []): JsonResponse
    {
        [$body, $status] = self::getBaseResponse('TOO_MANY_REQUESTS', $message, [], $additional);

        return response()->json($body, $status);
    }

    /**
     * Generate a validation error (422) JSON response.
     *
     * @param  MessageBag|ViewErrorBag|array<string, string[]>  $errors
     * @param  array<string, mixed>  $additional
     */
    public static function validationError(MessageBag|ViewErrorBag|array $errors, ?string $message = null, array $additional = []): JsonResponse
    {
        if (is_array($errors)) {
            $errors = new MessageBag($errors);
        }
        [$body, $status] = self::getBaseResponse('VALIDATION_ERROR', $message, $errors, $additional);

        return response()->json($body, $status);
    }
}
