<?php

declare(strict_types=1);

namespace App\Domains\Note\Presentation\Api\Controllers;

use App\Core\Presentation\Http\Controllers\Controller;
use App\Domains\Note\Presentation\Api\Requests\Stack\ListStacksRequest;
use App\Domains\Note\Presentation\Api\Requests\Stack\GetStackRequest;
use App\Domains\Note\Presentation\Api\Requests\Stack\CreateStackRequest;
use App\Domains\Note\Presentation\Api\Requests\Stack\UpdateStackRequest;
use App\Domains\Note\Presentation\Api\Requests\Stack\DeleteStackRequest;
use App\Domains\Note\Presentation\Resources\StackCollection;
use App\Domains\Note\Presentation\Resources\StackResource;
use App\Domains\Note\Application\Queries\Stack\ListStacks;
use App\Domains\Note\Application\Queries\Stack\GetStackById;
use App\Domains\Note\Application\Commands\Stack\CreateStack;
use App\Domains\Note\Application\Commands\Stack\DeleteStack;
use App\Domains\Note\Application\Commands\Stack\UpdateStack;

final class StacksController extends Controller
{
    public function list(ListStacksRequest $request)
    {
        return StackCollection::make(
            $this->queryBus->handle(ListStacks::fromRequest($request)),
            $request
        );
    }

    public function get(GetStackRequest $request)
    {
        return StackResource::found(
            $this->queryBus->handle(GetStackById::fromRequest($request)),
            $request
        );
    }

    public function create(CreateStackRequest $request)
    {
        return StackResource::created(
            $this->commandBus->handle(CreateStack::fromRequest($request)),
            $request
        );
    }

    public function update(UpdateStackRequest $request)
    {
        return StackResource::updated(
            $this->commandBus->handle(UpdateStack::fromRequest($request)),
            $request
        );
    }

    public function delete(DeleteStackRequest $request)
    {
        $this->commandBus->handle(DeleteStack::fromRequest($request));

        return StackResource::deleted($request);
    }
}
