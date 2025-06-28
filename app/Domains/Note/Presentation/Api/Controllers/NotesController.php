<?php

declare(strict_types=1);

namespace App\Domains\Note\Presentation\Api\Controllers;

use App\Core\Presentation\Http\Controllers\Controller;
use App\Domains\Note\Presentation\Api\Requests\Note\ListNotesRequest;
use App\Domains\Note\Presentation\Api\Requests\Note\GetNoteRequest;
use App\Domains\Note\Presentation\Api\Requests\Note\CreateNoteRequest;
use App\Domains\Note\Presentation\Api\Requests\Note\UpdateNoteRequest;
use App\Domains\Note\Presentation\Api\Requests\Note\DeleteNoteRequest;
use App\Domains\Note\Presentation\Resources\NoteCollection;
use App\Domains\Note\Presentation\Resources\NoteResource;
use App\Domains\Note\Application\Queries\Note\ListNotes;
use App\Domains\Note\Application\Queries\Note\GetNoteById;
use App\Domains\Note\Application\Commands\Note\CreateNote;
use App\Domains\Note\Application\Commands\Note\DeleteNote;
use App\Domains\Note\Application\Commands\Note\UpdateNote;

final class NotesController extends Controller
{
    public function list(ListNotesRequest $request)
    {
        return NoteCollection::make(
            $this->queryBus->handle(ListNotes::fromRequest($request)),
            $request
        );
    }

    public function get(GetNoteRequest $request)
    {
        return NoteResource::found(
            $this->queryBus->handle(GetNoteById::fromRequest($request)),
            $request
        );
    }

    public function create(CreateNoteRequest $request)
    {
        return NoteResource::created(
            $this->commandBus->handle(CreateNote::fromRequest($request)),
            $request
        );
    }

    public function update(UpdateNoteRequest $request)
    {
        return NoteResource::updated(
            $this->commandBus->handle(UpdateNote::fromRequest($request)),
            $request
        );
    }

    public function delete(DeleteNoteRequest $request)
    {
        $this->commandBus->handle(DeleteNote::fromRequest($request));

        return NoteResource::deleted($request);
    }
}
