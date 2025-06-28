<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Services;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Domains\Note\Application\Services\Contracts\NoteServiceContract;
use App\Domains\Note\Domain\Entities\Note;
use App\Domains\Note\Domain\Entities\Contracts\NoteEntityContract;
use App\Domains\Note\Domain\Events\Note\NoteCreated;
use App\Domains\Note\Domain\Events\Note\NoteDeleted;
use App\Domains\Note\Domain\Events\Note\NoteUpdated;
use App\Domains\Note\Domain\Repositories\NotesRepository;
use App\Domains\Note\Domain\Services\NoteService as NoteDomainService;

final class NoteService implements NoteServiceContract
{
    public function __construct(
        protected NoteDomainService $noteService,
        protected NotesRepository $notesRepository
    ) {}

    public function create(NoteEntityContract|array $note): NoteEntityContract
    {
        return DB::transaction(function () use ($note): NoteEntityContract {
            /** @var NoteEntityContract $note */
            $note = $this->noteService->create($note);

            NoteCreated::dispatch($note);

            return $note;
        });
    }

    public function update(string|UuidInterface|NoteEntityContract $note, array $data = []): NoteEntityContract
    {
        return DB::transaction(function () use ($note, $data): NoteEntityContract {
            if (!($note instanceof NoteEntityContract)) {
                $note = $this->notesRepository->findById($note);
            }

            $this->noteService->update($note, $data);

            NoteUpdated::dispatch($note);

            return $note;
        });
    }

    public function delete(string|UuidInterface|NoteEntityContract $note): bool
    {
        return DB::transaction(function () use ($note): bool {
            if (!($note instanceof NoteEntityContract)) {
                $note = new Note(id: is_string($note) ? Uuid::fromString($note) : $note);
            }

            if ($deleted = $this->noteService->delete($note)) {
                NoteDeleted::dispatch($note);
            }

            return $deleted;
        });
    }

    public function deleteNotes(Collection|array|int|string $ids): bool
    {
        return DB::transaction(function () use ($ids): bool {
            if ($deleted = $this->noteService->deleteNotes($ids)) {
                if ($ids instanceof Collection) {
                    $ids = $ids->toArray();
                } elseif (is_string($ids)) {
                    $ids = [$ids];
                }

                foreach ($ids as $id) {
                    NoteDeleted::dispatch(new Note(
                        id: is_string($id) ? Uuid::fromString($id) : $id)
                    );
                }
            }

            return $deleted;
        });
    }
}
