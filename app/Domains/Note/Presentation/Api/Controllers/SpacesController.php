<?php

declare(strict_types=1);

namespace App\Domains\Note\Presentation\Api\Controllers;

use App\Core\Presentation\Http\Controllers\Controller;
use App\Domains\Note\Presentation\Api\Requests\Space\ListSpacesRequest;
use App\Domains\Note\Presentation\Api\Requests\Space\GetSpaceRequest;
use App\Domains\Note\Presentation\Api\Requests\Space\CreateSpaceRequest;
use App\Domains\Note\Presentation\Api\Requests\Space\UpdateSpaceRequest;
use App\Domains\Note\Presentation\Api\Requests\Space\DeleteSpaceRequest;
use App\Domains\Note\Presentation\Resources\SpaceCollection;
use App\Domains\Note\Presentation\Resources\SpaceResource;
use App\Domains\Note\Application\Queries\Space\ListSpaces;
use App\Domains\Note\Application\Queries\Space\GetSpaceById;
use App\Domains\Note\Application\Commands\Space\CreateSpace;
use App\Domains\Note\Application\Commands\Space\DeleteSpace;
use App\Domains\Note\Application\Commands\Space\UpdateSpace;

final class SpacesController extends Controller
{
    public function list(ListSpacesRequest $request)
    {
        return SpaceCollection::make(
            $this->queryBus->handle(ListSpaces::fromRequest($request)),
            $request
        );
    }

    public function get(GetSpaceRequest $request)
    {
        return SpaceResource::found(
            $this->queryBus->handle(GetSpaceById::fromRequest($request)),
            $request
        );
    }

    public function create(CreateSpaceRequest $request)
    {
        return SpaceResource::created(
            $this->commandBus->handle(CreateSpace::fromRequest($request)),
            $request
        );
    }

    public function update(UpdateSpaceRequest $request)
    {
        return SpaceResource::updated(
            $this->commandBus->handle(UpdateSpace::fromRequest($request)),
            $request
        );
    }

    public function delete(DeleteSpaceRequest $request)
    {
        $this->commandBus->handle(DeleteSpace::fromRequest($request));

        return SpaceResource::deleted($request);
    }
}
