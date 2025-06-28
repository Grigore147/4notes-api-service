<?php

declare(strict_types=1);

namespace App\Domains\Note\Presentation\Api\Controllers;

use Illuminate\Http\Request;
use App\Core\Presentation\Http\Controllers\Controller;
use App\Domains\Note\Presentation\Resources\NotebookResource;
use App\Domains\Note\Presentation\Resources\NotebookCollection;
use App\Domains\Note\Application\Queries\Notebook\ListNotebooks;
use App\Domains\Note\Application\Commands\Notebook\CreateNotebook;
use App\Domains\Note\Application\Commands\Notebook\DeleteNotebook;
use App\Domains\Note\Application\Commands\Notebook\UpdateNotebook;
use App\Domains\Note\Application\Queries\Notebook\GetNotebookById;
use App\Domains\Note\Presentation\Api\Requests\Notebook\GetNotebookRequest;
use App\Domains\Note\Presentation\Api\Requests\Notebook\ListNotebooksRequest;
use App\Domains\Note\Presentation\Api\Requests\Notebook\CreateNotebookRequest;
use App\Domains\Note\Presentation\Api\Requests\Notebook\DeleteNotebookRequest;
use App\Domains\Note\Presentation\Api\Requests\Notebook\UpdateNotebookRequest;

final class NotebooksController extends Controller
{
    public function list(ListNotebooksRequest $request)
    {
        return NotebookCollection::make(
            $this->queryBus->handle(ListNotebooks::fromRequest($request)),
            $request
        );
    }

    public function get(GetNotebookRequest $request)
    {
        return NotebookResource::found(
            $this->queryBus->handle(GetNotebookById::fromRequest($request)),
            $request
        );
    }

    public function create(CreateNotebookRequest $request)
    {
        return NotebookResource::created(
            $this->commandBus->handle(CreateNotebook::fromRequest($request)),
            $request
        );
    }

    public function update(UpdateNotebookRequest $request)
    {
        return NotebookResource::updated(
            $this->commandBus->handle(UpdateNotebook::fromRequest($request)),
            $request
        );
    }

    public function delete(DeleteNotebookRequest $request)
    {
        $this->commandBus->handle(DeleteNotebook::fromRequest($request));

        return NotebookResource::deleted($request);
    }
}
