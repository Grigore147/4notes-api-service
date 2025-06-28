<?php

declare(strict_types=1);

namespace App\Domains\Note\Infrastructure\Repositories;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use App\Core\Infrastructure\Repositories\Repository;
use App\Core\Domain\Entities\EntityContract;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Notebook;
use App\Domains\Note\Domain\Repositories\NotebooksRepository as NotebooksRepositoryContract;
use App\Domains\Note\Domain\Entities\Notebook as NotebookEntity;
use App\Domains\Note\Domain\Entities\Contracts\NotebookEntityContract;

/**
 * NotebooksRepository
 *
 * @inheritDoc
 *
 * @method all(bool $toEntities = true): Collection;
 * @method find(array $filters = [], bool $paginate = true, bool $toEntities = true): Collection|LengthAwarePaginator;
 * @method findOne(array $filters = [], bool $toEntity = true): Notebook|NotebookEntityContract|null;
 * @method findById(string|UuidInterface|NotebookEntityContract $id, array $filters = [], bool $toEntity = true): Notebook|NotebookEntityContract|null;
 * @method getById(string|UuidInterface|NotebookEntityContract $id, array $filters = [], bool $toEntity = true): Notebook|NotebookEntityContract;
 * @method create(NotebookEntityContract|array $entity, bool $toEntity = true): Notebook|NotebookEntityContract;
 * @method update(string|UuidInterface|Notebook|NotebookEntityContract $item, array $data = [], bool $toEntity = true): Notebook|NotebookEntityContract;
 * @method save(Notebook|NotebookEntityContract|array $item, bool $toEntity = true): Notebook|NotebookEntityContract;
 * @method delete(string|UuidInterface|NotebookEntityContract|Notebook $id): bool;
 * @method deleteMany(Collection|array|int|string $ids): bool;
 * @method toModel(NotebookEntityContract|array $entity): Notebook;
 * @method toEntity(Notebook|array|null $item): ?NotebookEntityContract;
 * @method freshEntity(NotebookEntityContract $entity, array $filters = []): NotebookEntityContract;
 * @method toEntities(Collection|LengthAwarePaginator $items): Collection|LengthAwarePaginator;
 */
final class NotebooksRepository extends Repository implements NotebooksRepositoryContract
{
    public const MODEL = Notebook::class;

    public const ENTITY = NotebookEntity::class;

    /**
     * Convert Entity to Model
     *
     * @param NotebookEntityContract|array $item
     * @return Notebook
     */
    public function toModel(EntityContract|array $item): Model
    {
        return Notebook::make(
            $item instanceof NotebookEntityContract ? [
                'id' => $item->getId(),
                'user_id' => $item->getUserId(),
                'space_id' => $item->getSpaceId(),
                'stack_id' => $item->getStackId(),
                'name' => $item->getName(),
                // NOTE: Eloquent will automatically handle these fields
                // 'created_at' => $item->getCreatedAt(),
                // 'updated_at' => $item->getUpdatedAt()
            ] : $item
        );
    }

    /**
     * Convert Model to Entity
     *
     * @param Notebook|array|null $item
     * @return NotebookEntityContract
     */
    public function toEntity(Model|array|null $notebook): ?EntityContract
    {
        if ($notebook === null) { return null; }

        if (is_array($notebook)) {
            return NotebookEntity::fromArray($notebook);
        }

        return new NotebookEntity(
            id: Uuid::fromString($notebook->id),
            userId: Uuid::fromString($notebook->user_id),
            spaceId: $notebook->space_id ? Uuid::fromString($notebook->space_id) : null,
            space: $notebook->relation('space'),
            stackId: $notebook->stack_id ? Uuid::fromString($notebook->stack_id) : null,
            stack: $notebook->relation('stack'),
            name: $notebook->name,
            createdAt: $notebook->created_at,
            updatedAt: $notebook->updated_at
        );
    }
}
