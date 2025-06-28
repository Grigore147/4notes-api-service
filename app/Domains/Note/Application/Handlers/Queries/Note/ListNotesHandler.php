<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Handlers\Queries\Note;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Domains\Note\Application\Queries\Note\ListNotes;
use App\Domains\Note\Domain\Repositories\NotesRepository as NotesRepositoryContract;
use App\Domains\Note\Infrastructure\Repositories\NotesRepository;

final class ListNotesHandler
{
    /**
     * GetNoteHandler constructor.
     *
     * @param NotesRepository $notesRepository
     */
    public function __construct(
        private NotesRepositoryContract $notesRepository
    ) {}

    public function handle(ListNotes $query): Collection|LengthAwarePaginator
    {
        return $this->notesRepository->find($query->filters);
    }
}
