<?php

declare(strict_types=1);

namespace App\Core\Exceptions;

use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exceptions\ThrottleRequestsException as LaravelThrottleRequestsException;
use App\Core\Support\Enums\ResponseStatus;

final class ThrottleRequestsException extends LaravelThrottleRequestsException
{
    public function render(Request $request): Response|JsonResponse|bool
    {
        // Let default render method handle the exception if the request is not expecting JSON
        if (!$request->expectsJson()) { return false; }

        $headers = [
            'X-Request-Id' => request()->header('X-Request-Id', Str::uuid()),
            'X-Correlation-Id' => request()->header('X-Correlation-Id', Str::uuid())
        ];

        return response()->json([
            'status' => ResponseStatus::ERROR,
            'code' => HttpResponse::HTTP_TOO_MANY_REQUESTS,
            'message' => 'Too Many Requests',
            'meta' => [
                'requestId' => $headers['X-Request-Id'],
                'correlationId' => $headers['X-Correlation-Id']
            ]
        ], HttpResponse::HTTP_TOO_MANY_REQUESTS, $headers);
    }
}
