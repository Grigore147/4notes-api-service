<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Handlers\Queries\Note;

use App\Domains\Note\Application\Queries\Note\FindNoteById;
use App\Domains\Note\Infrastructure\Repositories\NotesRepository;
use App\Domains\Note\Domain\Repositories\NotesRepository as NotesRepositoryContract;
use App\Domains\Note\Domain\Entities\Contracts\NoteEntityContract;

final class FindNoteByIdHandler
{
    /**
     * GetNoteHandler constructor.
     *
     * @param NotesRepository $notesRepository
     */
    public function __construct(
        private NotesRepositoryContract $notesRepository
    ) {}

    public function handle(FindNoteById $query): ?NoteEntityContract
    {
        return $this->notesRepository->findById($query->id);
    }
}
