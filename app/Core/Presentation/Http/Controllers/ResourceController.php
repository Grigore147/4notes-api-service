<?php

declare(strict_types=1);

namespace App\Core\Presentation\Http\Controllers;

use App\Core\Application\QueryBus\Query;
use Illuminate\Http\Request;
use App\Core\Application\CommandBus\Command;
use App\Core\Presentation\Resources\JsonResource;
use App\Core\Presentation\Resources\ResourceCollection;

/**
 * Resource Controller
 *
 * This is a base agnostic controller for API Resources.
 *
 * NOTE: This is just an example of a very opinionated / experimental implementation and it is not meant
 *       to follow any specific standard / convention / pattern or best practice.
 */
abstract class ResourceController extends ApiController // @pest-arch-ignore-line
{
    public const NAME = 'resource';
    public const RESOURCE = JsonResource::class;
    public const RESOURCE_COLLECTION = ResourceCollection::class;

    public const LIST_REQUEST = Request::class;
    public const GET_REQUEST = Request::class;
    public const CREATE_REQUEST = Request::class;
    public const UPDATE_REQUEST = Request::class;
    public const DELETE_REQUEST = Request::class;

    public const LIST_QUERY = Query::class;
    public const GET_QUERY = Query::class;
    public const CREATE_COMMAND = Command::class;
    public const UPDATE_COMMAND = Command::class;
    public const DELETE_COMMAND = Command::class;

    public function list(Request $request)
    {
        $request = static::LIST_REQUEST::createFrom($request);

        $this->authorizeRequest($request);

        return static::RESOURCE_COLLECTION::make(
            $this->queryBus->handle(static::LIST_QUERY::fromRequest($request)),
            $request
        );
    }

    public function get(Request $request)
    {
        $request = static::GET_REQUEST::createFrom($request);

        $this->authorizeRequest($request);

        return static::RESOURCE::found(
            $this->queryBus->handle(static::GET_QUERY::fromRequest($request)),
            $request
        );
    }

    public function create(Request $request)
    {
        $request = static::CREATE_REQUEST::createFrom($request);

        $this->authorizeRequest($request);

        return static::RESOURCE::created(
            $this->commandBus->handle(static::CREATE_COMMAND::fromRequest($request)),
            $request
        );
    }

    public function update(Request $request)
    {
        $request = static::UPDATE_REQUEST::createFrom($request);

        $this->authorizeRequest($request);

        return static::RESOURCE::updated(
            $this->commandBus->handle(static::UPDATE_COMMAND::fromRequest($request)),
            $request
        );
    }

    public function delete(Request $request)
    {
        $request = static::DELETE_REQUEST::createFrom($request);

        $this->authorizeRequest($request);

        $this->commandBus->handle(static::DELETE_COMMAND::fromRequest($request));

        return static::RESOURCE::deleted($request);
    }
}
