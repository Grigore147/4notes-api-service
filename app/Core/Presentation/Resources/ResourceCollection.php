<?php

declare(strict_types=1);

namespace App\Core\Presentation\Resources;

use ReflectionClass;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Http\Resources\Json\ResourceCollection as IlluminateResourceCollection;
use App\Core\Domain\Entities\EntityContract;

abstract class ResourceCollection extends IlluminateResourceCollection // @pest-arch-ignore-line
{
    protected array $requestedFields = ['*'];

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @param  array  $requestedFields
     * @param  bool  $keepQuery
     * @return void
     */
    public function __construct(mixed $resource, Request|array $requestedFields = ['*'], bool $keepQuery = true)
    {
        if ($requestedFields instanceof Request) {
            if ($keepQuery) {
                $this->withQuery($requestedFields->query());
            }
            
            $requestedFields = $requestedFields->getRequestedFields(
                (new ReflectionClass($this->collects))->getConstant('NAME')
            );
        }

        $this->requestedFields = $requestedFields;
        
        $this->resource = $this->collectResource($resource);
    }

    /**
     * Map the given collection resource into its individual resources.
     *
     * @param  mixed  $resource
     * @return mixed
     */
    protected function collectResource($resource) // @pest-arch-ignore-line
    {
        if ($resource instanceof MissingValue) {
            return $resource;
        }

        if (is_array($resource)) {
            $resource = new Collection($resource);
        }

        $collects = $this->collects();

        $this->collection = $collects && !$resource->first() instanceof $collects
            ? $resource->map(function ($item) use ($collects) {
                return $item instanceof EntityContract ?
                    $collects::fromEntity($item, $this->requestedFields)
                : new $collects($item);
            })
            : $resource->toBase();

        return ($resource instanceof AbstractPaginator || $resource instanceof AbstractCursorPaginator)
                    ? $resource->setCollection($this->collection)
                    : $this->collection;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => 'success',
            'code' => '200',
            'message' => 'OK',
            'meta' => [
                'requestId' => $request->header('X-Request-Id', Uuid::uuid4()),
                'correlationId' => $request->header('X-Correlation-Id', Uuid::uuid4()),
                'type' => (new ReflectionClass($this))->getShortName()
            ],
            'data' => $this->collection
        ];
    }

    /**
     * Customize the pagination information for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array $paginated
     * @param  array $default
     * @return array
     */
    public function paginationInformation(Request $request, array $paginated, array $default): array
    {
        return [
            'meta' => [
                'pagination' => [
                    'from'        => $paginated['from'],
                    'to'          => $paginated['to'],
                    'total'       => $paginated['total'],
                    'perPage'     => $paginated['per_page'],
                    'currentPage' => $paginated['current_page'],
                    'lastPage'    => $paginated['last_page'],
                    'links'       => $default['links']
                ]
            ]
        ];
    }
}
