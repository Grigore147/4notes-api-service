<?php

declare(strict_types=1);

namespace App\Domains\Note\Infrastructure\Repositories;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use App\Core\Domain\Entities\EntityContract;
use App\Core\Infrastructure\Repositories\Repository;
use App\Domains\Note\Domain\Repositories\NotesRepository as NotesRepositoryContract;
use App\Domains\Note\Domain\Entities\Note as NoteEntity;
use App\Domains\Note\Domain\Entities\Contracts\NoteEntityContract;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Note;

/**
 * NotesRepository
 *
 * @inheritDoc
 *
 * @method all(bool $toEntities = true): Collection;
 * @method find(array $filters = [], bool $paginate = true, bool $toEntities = true): Collection|LengthAwarePaginator;
 * @method findOne(array $filters = [], bool $toEntity = true): Note|NoteEntityContract|null;
 * @method findById(string|UuidInterface|NoteEntityContract $id, array $filters = [], bool $toEntity = true): Note|NoteEntityContract|null;
 * @method getById(string|UuidInterface|NoteEntityContract $id, array $filters = [], bool $toEntity = true): Note|NoteEntityContract;
 * @method create(NoteEntityContract|array $entity, bool $toEntity = true): Note|NoteEntityContract;
 * @method update(string|UuidInterface|Note|NoteEntityContract $item, array $data = [], bool $toEntity = true): Note|NoteEntityContract;
 * @method save(Note|NoteEntityContract|array $item, bool $toEntity = true): Note|NoteEntityContract;
 * @method delete(string|UuidInterface|NoteEntityContract|Note $id): bool;
 * @method deleteMany(Collection|array|int|string $ids): bool;
 * @method toModel(NoteEntityContract|array $entity): Note;
 * @method toEntity(Note|array|null $item): ?NoteEntityContract;
 * @method freshEntity(NoteEntityContract $entity, array $filters = []): NoteEntityContract;
 * @method toEntities(Collection|LengthAwarePaginator $items): Collection|LengthAwarePaginator;
 */
final class NotesRepository extends Repository implements NotesRepositoryContract
{
    public const MODEL = Note::class;

    public const ENTITY = NoteEntity::class;

    /**
     * Convert Entity to Model
     *
     * @param NoteEntityContract|array $item
     * @return Note
     */
    public function toModel(EntityContract|array $item): Model
    {
        return Note::make(
            $item instanceof NoteEntityContract ? [
                'id' => $item->getId(),
                'user_id' => $item->getUserId(),
                'notebook_id' => $item->getNotebookId(),
                'title' => $item->getTitle(),
                'content' => $item->getContent(),
                // NOTE: Eloquent will automatically handle these fields
                // 'created_at' => $item->getCreatedAt(),
                // 'updated_at' => $item->getUpdatedAt()
            ] : $item
        );
    }

    /**
     * Convert Model to Entity
     *
     * @param Note|array|null $item
     * @return NoteEntityContract
     */
    public function toEntity(Model|array|null $note): ?EntityContract
    {
        if ($note === null) { return null; }

        if (is_array($note)) {
            return NoteEntity::fromArray($note);
        }

        return new NoteEntity(
            id: Uuid::fromString($note->id),
            userId: Uuid::fromString($note->user_id),
            notebookId: $note->notebook_id ? Uuid::fromString($note->notebook_id) : null,
            notebook: $note->relation('notebook'),
            title: $note->title,
            content: $note->content,
            createdAt: $note->created_at,
            updatedAt: $note->updated_at
        );
    }
}
