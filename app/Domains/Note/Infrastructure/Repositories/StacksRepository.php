<?php

declare(strict_types=1);

namespace App\Domains\Note\Infrastructure\Repositories;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use App\Core\Domain\Entities\EntityContract;
use App\Core\Infrastructure\Repositories\Repository;
use App\Domains\Note\Domain\Entities\Stack as StackEntity;
use App\Domains\Note\Domain\Entities\Contracts\StackEntityContract;
use App\Domains\Note\Domain\Repositories\StacksRepository as StacksRepositoryContract;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Stack;

/**
 * StacksRepository
 *
 * @inheritDoc
 *
 * @method all(bool $toEntities = true): Collection;
 * @method find(array $filters = [], bool $paginate = true, bool $toEntities = true): Collection|LengthAwarePaginator;
 * @method findOne(array $filters = [], bool $toEntity = true): Stack|StackEntityContract|null;
 * @method findById(string|UuidInterface|StackEntityContract $id, array $filters = [], bool $toEntity = true): Stack|StackEntityContract|null;
 * @method getById(string|UuidInterface|StackEntityContract $id, array $filters = [], bool $toEntity = true): Stack|StackEntityContract;
 * @method create(StackEntityContract|array $entity, bool $toEntity = true): Stack|StackEntityContract;
 * @method update(string|UuidInterface|Stack|StackEntityContract $item, array $data = [], bool $toEntity = true): Stack|StackEntityContract;
 * @method save(Stack|StackEntityContract|array $item, bool $toEntity = true): Stack|StackEntityContract;
 * @method delete(string|UuidInterface|StackEntityContract|Stack $id): bool;
 * @method deleteMany(Collection|array|int|string $ids): bool;
 * @method toModel(StackEntityContract|array $entity): Stack;
 * @method toEntity(Stack|array|null $item): ?StackEntityContract;
 * @method freshEntity(StackEntityContract $entity, array $filters = []): StackEntityContract;
 * @method toEntities(Collection|LengthAwarePaginator $items): Collection|LengthAwarePaginator;
 */
final class StacksRepository extends Repository implements StacksRepositoryContract
{
    public const MODEL = Stack::class;

    public const ENTITY = StackEntity::class;

    /**
     * Convert Entity to Model
     *
     * @param StackEntityContract|array $item
     * @return Stack
     */
    public function toModel(EntityContract|array $item): Model
    {
        return Stack::make(
            $item instanceof StackEntityContract ? [
                'id' => $item->getId(),
                'user_id' => $item->getUserId(),
                'space_id' => $item->getSpaceId(),
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
     * @param Stack|array|null $item
     * @return StackEntityContract
     */
    public function toEntity(Model|array|null $stack): ?EntityContract
    {
        if ($stack === null) { return null; }

        if (is_array($stack)) {
            return StackEntity::fromArray($stack);
        }

        return new StackEntity(
            id: Uuid::fromString($stack->id),
            userId: Uuid::fromString($stack->user_id),
            spaceId: Uuid::fromString($stack->space_id),
            space: $stack->relation('space'),
            name: $stack->name,
            createdAt: $stack->created_at,
            updatedAt: $stack->updated_at
        );
    }
}
