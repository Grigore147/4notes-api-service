<?php

declare(strict_types=1);

namespace App\Core\Domain\Repositories;

use Ramsey\Uuid\UuidInterface;
use Illuminate\Support\Collection;
use App\Core\Domain\Entities\EntityContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * RepositoryContract
 */
interface RepositoryContract
{
    /**
     * Get all Items
     *
     * @param bool $toEntities
     * @return Collection
     */
    public function all(bool $toEntities = true): Collection;

    /**
     * Find Items
     *
     * @param array $filters
     * @param bool $paginate
     * @param bool $toEntities
     * @return Collection|LengthAwarePaginator
     */
    public function find(array $filters = [], bool $paginate = true, bool $toEntities = true): Collection|LengthAwarePaginator;

    /**
     * Find One Item
     *
     * @param array $filters
     * @param bool $toEntity
     * @return Model|EntityContract|null
     */
    public function findOne(array $filters = [], bool $toEntity = true): Model|EntityContract|null;

    /**
     * Find Item by ID
     *
     * @param string|UuidInterface|EntityContract $id
     * @param array $filters
     * @param bool $toEntity
     * @return Model|EntityContract|null
     */
    public function findById(string|UuidInterface|EntityContract $id, array $filters = [], bool $toEntity = true): Model|EntityContract|null;

     /**
     * Find Item by ID or fail
     *
     * @param string|UuidInterface|EntityContract $id
     * @param array $filters
     * @param bool $toEntity
     * @return Model|EntityContract
     * @throws ModelNotFoundException
     */
    public function getById(string|UuidInterface|EntityContract $id, array $filters = [], bool $toEntity = true): Model|EntityContract;

    /**
     * Check if Item exists
     *
     * @param array|int|string|UuidInterface $id
     * @param string $field
     * @return bool
     */
    public function exists(array|int|string|UuidInterface $id, string $field = 'id'): bool;

    /**
     * Count Items
     *
     * @param array $filters
     * @return int
     */
    public function count(array $filters = []): int;

    /**
     * Create Item
     *
     * @param EntityContract|array $entity
     * @param bool $toEntity
     * @return Model|EntityContract
     */
    public function create(EntityContract|array $entity, bool $toEntity = true): Model|EntityContract;

    /**
     * Update Item
     *
     * @param string|UuidInterface|EntityContract $item
     * @param array $data
     * @param bool $toEntity
     * @return Model|EntityContract
     */
    public function update(string|UuidInterface|Model|EntityContract $item, array $data = [], bool $toEntity = true): Model|EntityContract;
    
    /**
     * Update Item or create if it doesn't exist
     *
     * @param Model|EntityContract|array $item
     * @param bool $toEntity
     * @return Model|EntityContract
     */
    public function save(Model|EntityContract|array $item, bool $toEntity = true): Model|EntityContract;

    /**
     * Delete Item
     *
     * @param string|UuidInterface|EntityContract|Model $id
     * @return bool
     */
    public function delete(string|UuidInterface|EntityContract|Model $id): bool;
    
    /**
     * Delete multiple Items
     *
     * @param Collection|array|int|string $ids
     * @return bool True if all items were deleted
     */
    public function deleteMany(Collection|array|int|string $ids): bool;

    /**
     * Convert Entity to Model
     *
     * @param EntityContract|array $entity
     * @return Model
     */
    public function toModel(EntityContract|array $entity): Model;

    /**
     * Convert Model to Entity
     *
     * @param Model|array|null $item
     * @return EntityContract
     */
    public function toEntity(Model|array|null $item): ?EntityContract;

    /**
     * Get a refreshed instance of the entity from the database
     *
     * @param EntityContract $item
     * @param array $filters
     * @return EntityContract
     */
    public function freshEntity(EntityContract $entity, array $filters = []): EntityContract;

    /**
     * Convert Model Collection to Entities Collection
     *
     * @param Collection|LengthAwarePaginator $items
     * @return Collection|LengthAwarePaginator
     */
    public function toEntities(Collection|LengthAwarePaginator $items): Collection|LengthAwarePaginator;
}
