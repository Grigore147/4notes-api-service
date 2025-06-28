<?php

declare(strict_types=1);

namespace App\Core\Presentation\Http\Traits;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Core\Support\Enums\ResponseStatus;

trait SendsResponses
{
    public function response(
        null|array|string|object $content = null,
        ?string $message = 'OK',
        ?array $meta = [],
        ?array $headers = [],
        ?int $statusCode = Response::HTTP_OK,
        ?ResponseStatus $status = ResponseStatus::SUCCESS,
        ?bool $toJson = true
    ): JsonResponse|Response|ResponseFactory
    {
        $meta = [
            'requestId' => request()->header('X-Request-Id', Str::uuid()),
            'correlationId' => request()->header('X-Correlation-Id', Str::uuid()),
            ...$meta
        ];

        if ($toJson && !($content instanceof View)) {
            return response()->json([
                'status' => $status,
                'code' => $statusCode,
                'message' => $message,
                'meta' => $meta,
                'data' => $content
            ], $statusCode, $headers);
        }

        foreach ($meta as $key => $value) {
            $key = Str::prefix(Str::studlyKebab($key), 'X-');
            $headers[$key] = $value;
        }

        return response($content, $statusCode, $headers);
    }

    public function ok(
        null|array|string|object $content = null,
        ?string $message = 'OK',
        ?array $meta = [],
        ?array $headers = [],
        ?bool $toJson = true,
    ): JsonResponse|Response|ResponseFactory
    {
        return $this->response(
            content: $content,
            message: $message,
            meta: $meta,
            headers: $headers,
            statusCode: Response::HTTP_OK,
            status: ResponseStatus::SUCCESS,
            toJson: $toJson
        );
    }

    public function success(
        null|array|string|object $content = null,
        ?string $message = 'OK',
        ?array $meta = [],
        ?array $headers = [],
        ?bool $toJson = true,
    ): JsonResponse|Response|ResponseFactory
    {
        return $this->response(
            content: $content,
            message: $message,
            meta: $meta,
            headers: $headers,
            statusCode: Response::HTTP_OK,
            status: ResponseStatus::SUCCESS,
            toJson: $toJson
        );
    }

    public function created(
        null|array|string|object $content = null,
        ?string $message = 'Created',
        ?array $meta = [],
        ?array $headers = [],
        ?bool $toJson = true
    ): JsonResponse|Response|ResponseFactory
    {
        return $this->response(
            content: $content,
            message: $message,
            meta: $meta,
            headers: $headers,
            statusCode: Response::HTTP_CREATED,
            status: ResponseStatus::SUCCESS,
            toJson: $toJson
        );
    }

    public function accepted(
        null|array|string|object $content = null,
        ?string $message = 'Accepted',
        ?array $meta = [],
        ?array $headers = [],
        ?bool $toJson = true
    ): JsonResponse|Response|ResponseFactory
    {
        return $this->response(
            content: $content,
            message: $message,
            meta: $meta,
            headers: $headers,
            statusCode: Response::HTTP_ACCEPTED,
            status: ResponseStatus::SUCCESS,
            toJson: $toJson
        );
    }

    public function noContent(
        ?string $message = 'No Content',
        ?array $meta = [],
        ?array $headers = []
    ): JsonResponse|Response|ResponseFactory
    {
        return $this->response(
            message: $message,
            meta: $meta,
            headers: $headers,
            statusCode: Response::HTTP_NO_CONTENT,
            status: ResponseStatus::SUCCESS,
            toJson: false
        );
    }

    public function unauthorized(
        ?string $message = 'Unauthorized',
        ?array $meta = [],
        ?array $headers = [],
        ?bool $toJson = true
    ): JsonResponse|Response|ResponseFactory
    {
        return $this->response(
            message: $message,
            meta: $meta,
            headers: $headers,
            statusCode: Response::HTTP_UNAUTHORIZED,
            status: ResponseStatus::ERROR,
            toJson: $toJson
        );
    }

    public function forbidden(
        ?string $message = 'Forbidden',
        ?array $meta = [],
        ?array $headers = [],
        ?bool $toJson = true
    ): JsonResponse|Response|ResponseFactory
    {
        return $this->response(
            message: $message,
            meta: $meta,
            headers: $headers,
            statusCode: Response::HTTP_FORBIDDEN,
            status: ResponseStatus::ERROR,
            toJson: $toJson
        );
    }

    public function notFound(
        ?string $message = 'Not Found',
        ?array $meta = [],
        ?array $headers = [],
        ?bool $toJson = true
    ): JsonResponse|Response|ResponseFactory
    {
        return $this->response(
            message: $message,
            meta: $meta,
            headers: $headers,
            statusCode: Response::HTTP_NOT_FOUND,
            status: ResponseStatus::ERROR,
            toJson: $toJson
        );
    }

    public function methodNotAllowed(
        ?string $message = 'Method Not Allowed',
        ?array $meta = [],
        ?array $headers = [],
        ?bool $toJson = true
    ): JsonResponse|Response|ResponseFactory
    {
        return $this->response(
            message: $message,
            meta: $meta,
            headers: $headers,
            statusCode: Response::HTTP_METHOD_NOT_ALLOWED,
            status: ResponseStatus::ERROR,
            toJson: $toJson
        );
    }

    public function conflict(
        ?string $message = 'Conflict',
        ?array $meta = [],
        ?array $headers = [],
        ?bool $toJson = true
    ): JsonResponse|Response|ResponseFactory
    {
        return $this->response(
            message: $message,
            meta: $meta,
            headers: $headers,
            statusCode: Response::HTTP_CONFLICT,
            status: ResponseStatus::ERROR,
            toJson: $toJson
        );
    }

    public function unprocessableEntity(
        ?string $message = 'Unprocessable Entity',
        ?array $meta = [],
        ?array $headers = [],
        ?bool $toJson = true
    ): JsonResponse|Response|ResponseFactory
    {
        return $this->response(
            message: $message,
            meta: $meta,
            headers: $headers,
            statusCode: Response::HTTP_UNPROCESSABLE_ENTITY,
            status: ResponseStatus::ERROR,
            toJson: $toJson
        );
    }

    public function error(
        ?string $message = 'Internal Server Error',
        ?array $meta = [],
        ?array $headers = [],
        ?bool $toJson = true
    ): JsonResponse|Response|ResponseFactory
    {
        return $this->response(
            message: $message,
            meta: $meta,
            headers: $headers,
            statusCode: Response::HTTP_INTERNAL_SERVER_ERROR,
            status: ResponseStatus::ERROR,
            toJson: $toJson
        );
    }

    public function serviceUnavailable(
        ?string $message = 'Service Unavailable',
        ?array $meta = [],
        ?array $headers = [],
        ?bool $toJson = true
    ): JsonResponse|Response|ResponseFactory
    {
        return $this->response(
            message: $message,
            meta: $meta,
            headers: $headers,
            statusCode: Response::HTTP_SERVICE_UNAVAILABLE,
            status: ResponseStatus::ERROR,
            toJson: $toJson
        );
    }
}
