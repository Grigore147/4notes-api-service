<?php

declare(strict_types=1);

namespace App\Core\Presentation\Resources;

use ReflectionClass;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Core\Domain\Entities\EntityContract;
use Illuminate\Http\Resources\MissingValue;
use App\Core\Presentation\Resources\ResponsableResource;
use Illuminate\Http\Resources\Json\JsonResource as IlluminateJsonResource;

abstract class JsonResource extends IlluminateJsonResource // @pest-arch-ignore-line
{
    use ResponsableResource;

    /**
     * The resource name.
     *
     * @var string
     */
    public const NAME = '';

    /**
     * The requested fields.
     *
     * @var array
     */
    protected array $requestedFields = ['*'];

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @param  mixed  $requestedFields
     * @return void
     */
    public function __construct(mixed $resource, Request|array $requestedFields = ['*'])
    {
        parent::__construct($resource);

        if ($requestedFields instanceof Request) {
            $requestedFields = $requestedFields->getRequestedFields(static::NAME);
        }

        $this->requestedFields = $requestedFields;
    }

    /**
     * Create a new resource instance based on a request.
     *
     * @param  mixed  $resource
     * @param  Request  $request
     * @return $this
     */
    public static function fromRequest(mixed $resource, Request $request): static
    {
        return static::make($resource, $request);
    }

    /**
     * Create a new resource instance.
     *
     * @param  mixed  ...$parameters
     * @return static
     */
    public static function make(...$parameters)
    {
        $resource = $parameters[0] ?? null;

        if ($resource instanceof EntityContract) {
            return static::fromEntity($resource, $parameters[1] ?? ['*']);
        }

        return new static(...$parameters);
    }

    /**
     * Filter the resource data to only include the selected fields.
     *
     * @note This could be recursive to support nested fields
     *
     * @param  array  $requestedFields Requested fields
     * @param  array  $data Resource data
     * @return array
     */
    public function onlySelected(array $data): array
    {
        if ($this->requestedFields === ['*'] || ($this->requestedFields[static::NAME] ?? ['*']) === ['*']) {
            return $data;
        }

        return array_intersect_key(
            $data,
            array_flip($this->requestedFields[static::NAME] ?? [])
        );
    }

    /**
     * Retrieve an attribute if it is a loaded sub-resource.
     *
     * @param  mixed  $resource
     * @param  mixed  $default
     * @return \Illuminate\Http\Resources\MissingValue|mixed
     */
    public function whenHasResource(?JsonResource $resource, $default = null)
    {
        if ($resource !== null && $resource->resource !== null) {
            return $resource;
        }

        return func_num_args() === 2 ? value($default) : new MissingValue;
    }

    /**
     * Get any additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request): array
    {
        return [
            'status' => $this->responseStatus,
            'code' => $this->responseCode,
            'message' => $this->responseMessage,
            'meta' => [
                'requestId' => $request->header('X-Request-Id', Uuid::uuid4()),
                'correlationId' => $request->header('X-Correlation-Id', Uuid::uuid4()),
                'type' => (new ReflectionClass($this))->getShortName()
            ]
        ];
    }

    /**
     * Customize the response for a request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\JsonResponse  $response
     * @return void
     */
    public function withResponse(Request $request, JsonResponse $response)
    {
        // The only thing we're doing here is to move the data attribute to the bottom of the response
        // so that it's easier to read the response and match the structure of the Resource Collections.
        $data = $response->getData(true);
        $dataAttr = $data['data'] ?? [];

        unset($data['data']);

        $response->setData([...$data, 'data' => $dataAttr]);
    }

    /**
     * Create a new resource instance from the given entity.
     *
     * @param ?EntityContract $entity
     * @param array $requestedFields
     * @return static
     */
    public static function fromEntity(?EntityContract $entity, Request|array $requestedFields = ['*']): ?static
    {
        if ($entity === null) { return null; }

        return new static((object)$entity->getAttributes(), $requestedFields);
    }
}
