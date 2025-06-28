<?php

declare(strict_types=1);

namespace App\Domains\Note\Domain\Services;

use Ramsey\Uuid\UuidInterface;
use Illuminate\Support\Collection;
use App\Domains\Note\Domain\Repositories\NotesRepository;
use App\Domains\Note\Domain\Entities\Contracts\NoteEntityContract;
use App\Domains\Note\Domain\Services\Contracts\NoteService as NoteServiceContract;

/**
 * Note Domain Service
 *
 * This service is responsible for handling the domain / business logic for the Note domain.
 *
 * @package App\Domains\Notes\Domain\Services
 */
final class NoteService implements NoteServiceContract
{
    public function __construct(
        protected NotesRepository $notesRepository
    ){}

    public function create(NoteEntityContract|array $note): NoteEntityContract
    {
        return $this->notesRepository->create($note);
    }

    public function update(string|UuidInterface|NoteEntityContract $note, array $data = []): NoteEntityContract
    {
        return $this->notesRepository->update($note, $data);
    }

    public function delete(string|UuidInterface|NoteEntityContract $note): bool
    {
        return $this->notesRepository->delete($note);
    }

    public function deleteNotes(Collection|array|int|string $ids): bool
    {
        return $this->notesRepository->deleteMany($ids);
    }
}
