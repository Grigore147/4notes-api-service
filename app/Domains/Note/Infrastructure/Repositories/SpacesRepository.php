<?php

declare(strict_types=1);

namespace App\Domains\Note\Infrastructure\Repositories;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use App\Core\Infrastructure\Repositories\Repository;
use App\Core\Domain\Entities\EntityContract;
use App\Domains\Note\Domain\Entities\Space as SpaceEntity;
use App\Domains\Note\Domain\Entities\Contracts\SpaceEntityContract;
use App\Domains\Note\Domain\Repositories\SpacesRepository as SpacesRepositoryContract;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Space;

/**
 * SpacesRepository
 *
 * @inheritDoc
 *
 * @method all(bool $toEntities = true): Collection;
 * @method find(array $filters = [], bool $paginate = true, bool $toEntities = true): Collection|LengthAwarePaginator;
 * @method findOne(array $filters = [], bool $toEntity = true): Space|SpaceEntityContract|null;
 * @method findById(string|UuidInterface|SpaceEntityContract $id, array $filters = [], bool $toEntity = true): Space|SpaceEntityContract|null;
 * @method getById(string|UuidInterface|SpaceEntityContract $id, array $filters = [], bool $toEntity = true): Space|SpaceEntityContract;
 * @method create(SpaceEntityContract|array $entity, bool $toEntity = true): Space|SpaceEntityContract;
 * @method update(string|UuidInterface|Space|SpaceEntityContract $item, array $data = [], bool $toEntity = true): Space|SpaceEntityContract;
 * @method save(Space|SpaceEntityContract|array $item, bool $toEntity = true): Space|SpaceEntityContract;
 * @method delete(string|UuidInterface|SpaceEntityContract|Space $id): bool;
 * @method deleteMany(Collection|array|int|string $ids): bool;
 * @method toModel(SpaceEntityContract|array $entity): Space;
 * @method toEntity(Space|array|null $item): ?SpaceEntityContract;
 * @method freshEntity(SpaceEntityContract $entity, array $filters = []): SpaceEntityContract;
 * @method toEntities(Collection|LengthAwarePaginator $items): Collection|LengthAwarePaginator;
 */
final class SpacesRepository extends Repository implements SpacesRepositoryContract
{
    public const MODEL = Space::class;

    public const ENTITY = SpaceEntity::class;

    /**
     * Convert Entity to Model
     *
     * @param SpaceEntityContract|array $item
     * @return Space
     */
    public function toModel(EntityContract|array $item): Model
    {
        return Space::make(
            $item instanceof SpaceEntityContract ? [
                'id' => $item->getId(),
                'user_id' => $item->getUserId(),
                'name' => $item->getName(),
                'description' => $item->getDescription(),
                // NOTE: Eloquent will automatically handle these fields
                // 'created_at' => $item->getCreatedAt(),
                // 'updated_at' => $item->getUpdatedAt()
            ] : $item
        );
    }

    /**
     * Convert Model to Entity
     *
     * @param Space|array|null $item
     * @return SpaceEntityContract
     */
    public function toEntity(Model|array|null $space): ?EntityContract
    {
        if ($space === null) { return null; }

        if (is_array($space)) {
            return SpaceEntity::fromArray($space);
        }

        return new SpaceEntity(
            id: Uuid::fromString($space->id),
            userId: Uuid::fromString($space->user_id),
            name: $space->name,
            description: $space->description,
            createdAt: $space->created_at,
            updatedAt: $space->updated_at
        );
    }
}
