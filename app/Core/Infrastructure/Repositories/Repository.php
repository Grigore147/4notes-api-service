<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Repositories;

use RuntimeException;
use Ramsey\Uuid\UuidInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Core\Domain\Repositories\RepositoryContract;
use App\Core\Domain\Entities\EntityContract;

abstract class Repository implements RepositoryContract // @pest-arch-ignore-line
{
    /**
     * Model class
     *
     * @var class-string<Model>
     */
    public const MODEL = '';

    /**
     * Entity class
     *
     * @var class-string<EntityContract>
     */
    public const ENTITY = '';

    /**
     * @inheritDoc
     */
    public function all(bool $toEntities = true): Collection
    {
        $items = static::MODEL::get();

        return $toEntities ? $this->toEntities($items) : $items;
    }

    /**
     * @inheritDoc
     */
    public function find(array $filters = [], bool $paginate = true, bool $toEntities = true): Collection|LengthAwarePaginator
    {
        $items = static::MODEL::filter($filters)->{$paginate ? 'paginate' : 'get'}();

        return $toEntities ? $this->toEntities($items) : $items;
    }

    /**
     * @inheritDoc
     */
    public function findOne(array $filters = [], bool $toEntity = true): Model|EntityContract|null
    {
        /** @var Model|null $item */
        $item = static::MODEL::filter($filters)->first();

        return $toEntity ? $this->toEntity($item) : $item;
    }

    /**
     * @inheritDoc
     */
    public function findById(string|int|UuidInterface|EntityContract $id, array $filters = [], bool $toEntity = true): Model|EntityContract|null
    {
        $id = $id instanceof EntityContract ? $id->getId() : $id;

        /** @var Model|null $item */
        $item = static::MODEL::filter($filters)->find($id);

        return $toEntity ? $this->toEntity($item) : $item;
    }

    /**
     * @inheritDoc
     */
    public function getById(string|int|UuidInterface|EntityContract $id, array $filters = [], bool $toEntity = true): Model|EntityContract
    {
        $id = $id instanceof EntityContract ? $id->getId() : $id;

        /** @var Model|null $item */
        $item = static::MODEL::filter($filters)->findOrFail($id);

        return $toEntity ? $this->toEntity($item) : $item;
    }

    /**
     * @inheritDoc
     */
    public function exists(array|int|string|UuidInterface $id, string $field = 'id'): bool
    {
        if (is_array($id)) {
            return static::MODEL::filter($id)->exists();
        }

        return static::MODEL::where($field, '=', $id)->exists();
    }

    /**
     * @inheritDoc
     */
    public function count(array $filters = []): int
    {
        return static::MODEL::filter($filters)->count();
    }

    /**
     * @inheritDoc
     */
    public function create(EntityContract|array $item, bool $toEntity = true): Model|EntityContract
    {
        return $this->save($item, $toEntity);
    }

    /**
     * @inheritDoc
     */
    public function update(string|int|UuidInterface|Model|EntityContract $item, array $data = [], bool $toEntity = true): Model|EntityContract
    {
        if ($item instanceof Model) {
            $item->forceFill($data);
        } elseif ($item instanceof EntityContract) {
            $item->setAttributes($data); // @phpstan-ignore-line
        } else {
            $item = static::ENTITY::fromArray([
                'id' => $item,
                ...$data
            ]);
        }

        return $this->save($item, $toEntity);
    }

    /**
     * @inheritDoc
     */
    public function save(Model|EntityContract|array $item, bool $toEntity = true): Model|EntityContract
    {
        if (is_array($item)) {
            $item = static::ENTITY::fromArray($item);
        }

        if ($item instanceof Model) {
            if (!$item->save()) {
                throw new RuntimeException('Model could not be saved');
            }
        } else {
            $item = $this->toModel($item);

            $item = static::MODEL::updateOrCreate(
                ['id' => $item->id ?? null],
                $item->toArray()
            );
        }

        return $toEntity ? $this->toEntity($item) : $item;
    }

    /**
     * @inheritDoc
     */
    public function delete(string|UuidInterface|EntityContract|Model $item): bool
    {
        if ($item instanceof Model) {
            return (bool)$item->delete();
        }

        $id = $item instanceof EntityContract ? $item->getId() : $item;
        
        return (bool)static::MODEL::destroy($id);
    }

    /**
     * @inheritDoc
     */
    public function deleteMany(Collection|array|int|string $ids): bool
    {
        if (is_int($ids) || is_string($ids)) {
            $ids = [$ids];
        }

        $count = $ids instanceof Collection ? $ids->count() : count($ids);

        return static::MODEL::destroy($ids) === $count;
    }

    /**
     * @inheritDoc
     */
    public function toModel(EntityContract|array $item): Model
    {
        return static::MODEL::make(
            $item instanceof EntityContract ? $item->toArray() : $item
        );
    }

    /**
     * @inheritDoc
     */
    public function toEntity(Model|array|null $item): ?EntityContract
    {
        if ($item === null) { return null; }

        return static::ENTITY::fromArray(
            $item instanceof Model ? $item->toArray() : $item
        );
    }

    /**
     * @inheritDoc
     */
    public function freshEntity(EntityContract $entity, array $filters = []): EntityContract
    {
        return $this->toEntity(static::MODEL::filter($filters)->getById($entity->getId()));
    }

    /**
     * @inheritDoc
     */
    public function toEntities(Collection|LengthAwarePaginator $items): Collection|LengthAwarePaginator
    {
        if ($items instanceof Collection) {
            return $items->map(fn ($item) => $this->toEntity($item));
        }

        /** @var AbstractPaginator|LengthAwarePaginator $items */
        return $items->setCollection(
            $items->getCollection()->transform(
                fn ($item) => $this->toEntity($item)
            )
        );
    }
}
