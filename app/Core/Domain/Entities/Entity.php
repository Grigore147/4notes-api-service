<?php

declare(strict_types=1);

namespace App\Core\Domain\Entities;

use Carbon\Carbon;
use JsonSerializable;
use Ramsey\Uuid\UuidInterface;
use Illuminate\Contracts\Support\Jsonable;
use App\Core\Domain\Entities\EntityContract;

abstract class Entity implements EntityContract, Jsonable, JsonSerializable // @pest-arch-ignore-line
{
    use HasAttributes;

    /**
     * Entity ID
     *
     * @var ?UuidInterface
     */
    protected ?UuidInterface $id = null;
    /**
     * Entity Created At
     *
     * @var ?Carbon
     */
    protected ?Carbon $createdAt = null;
    /**
     * Entity Updated At
     *
     * @var ?Carbon
     */
    protected ?Carbon $updatedAt = null;

    /**
     * Get Entity ID
     *
     * @return ?UuidInterface
     */
    public function getId(): ?UuidInterface
    {
        return $this->getAttribute('id');
    }

    /**
     * Get Created At
     *
     * @return ?Carbon
     */
    public function getCreatedAt(): ?Carbon
    {
        return $this->getAttribute('createdAt');
    }

    /**
     * Get Updated At
     *
     * @return ?Carbon
     */
    public function getUpdatedAt(): ?Carbon
    {
        return $this->getAttribute('updatedAt');
    }

    /**
     * Create a new Entity instance from an array
     *
     * @param array $data
     * @param bool $asDirty
     * @return static
     */
    public static function fromArray(array $data, $asDirty = false): static
    {
        if ($asDirty) {
            return static::fromDirtyArray($data);
        }

        $classReflection = new \ReflectionClass(static::class);

        return $classReflection->newInstanceArgs($data);
    }

    /**
     * Create a new Entity instance from a dirty array
     *
     * @param array $data
     * @return static
     */
    public static function fromDirtyArray(array $data): static
    {
        $stack = new static();

        $reflectionMethod = new \ReflectionMethod($stack, 'set');

        return $reflectionMethod->invokeArgs($stack, $data);
    }

    /**
     * Get Entity attributes
     *
     * @param bool $dirtyOnly Get only dirty attributes
     * @return array
     */
    public function toArray(bool $dirtyOnly = false): array
    {
        return $dirtyOnly ? $this->getDirtyAttributes() : $this->getAttributes();
    }

    /**
     * Check if the Entity is new
     *
     * @return bool
     */
    public function isNew(): bool
    {
        return is_null($this->getId());
    }

    /**
     * Convert the Entity to a JSON string
     *
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this->getAttributes());
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
