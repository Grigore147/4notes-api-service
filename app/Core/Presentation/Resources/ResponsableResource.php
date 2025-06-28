<?php

declare(strict_types=1);

namespace App\Core\Presentation\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Core\Support\Enums\ResponseStatus;

trait ResponsableResource
{
    /**
     * Response status type
     *
     * @var string
     */
    protected ResponseStatus $responseStatus = ResponseStatus::SUCCESS;

    /**
     * Response code
     *
     * @var int
     */
    protected int $responseCode = Response::HTTP_OK;

    /**
     * Response message
     *
     * @var string
     */
    protected string $responseMessage = 'OK';

    /**
     * Set response status type
     *
     * @param  ResponseStatus  $status
     * @return static
     */
    public function withStatus(ResponseStatus $status): static
    {
        $this->responseStatus = $status;

        return $this;
    }

    /**
     * Set response code
     *
     * @param  int  $code
     * @return static
     */
    public function withCode(int $code): static
    {
        $this->responseCode = $code;

        return $this;
    }

    /**
     * Set response message
     *
     * @param  string  $message
     * @return static
     */
    public function withMessage(string $message): static
    {
        $this->responseMessage = $message;

        return $this;
    }

    /**
     * Resource Found
     *
     * @param  mixed  $resource
     * @param  Request  $request
     * @return JsonResponse
     */
    public static function found(
        mixed $resource, Request $request = null, string $message = 'Resource found'
    ): JsonResponse {
        return static::make($resource, $request)
            ->withStatus(ResponseStatus::SUCCESS)
            ->withCode(Response::HTTP_OK)
            ->withMessage($message)
            ->response($request)
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Resource Created
     *
     * @param  mixed  $resource
     * @param  Request  $request
     * @return JsonResponse
     */
    public static function created(
        mixed $resource, Request $request = null, string $message = 'Resource created'
    ): JsonResponse {
        return static::make($resource, $request)
            ->withStatus(ResponseStatus::SUCCESS)
            ->withCode(Response::HTTP_CREATED)
            ->withMessage($message)
            ->response($request)
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Resource Updated
     *
     * @param  mixed  $resource
     * @param  Request  $request
     * @return JsonResponse
     */
    public static function updated(
        mixed $resource, Request $request = null, string $message = 'Resource updated'
    ): JsonResponse {
        return static::make($resource, $request)
            ->withStatus(ResponseStatus::SUCCESS)
            ->withCode(Response::HTTP_OK)
            ->withMessage($message)
            ->response($request)
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Resource Deleted
     *
     * @param  mixed  $resource
     * @param  Request  $request
     * @return JsonResponse
     */
    public static function deleted(
        Request $request = null, string $message = 'Resource deleted'
    ): JsonResponse {
        return static::make(null, $request)
            ->withStatus(ResponseStatus::SUCCESS)
            ->withCode(Response::HTTP_NO_CONTENT)
            ->withMessage($message)
            ->response($request)
            ->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
