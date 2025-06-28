<?php

declare(strict_types=1);

namespace App\Core\Domain\Entities;

use Carbon\Carbon;
use App\Core\Domain\Entities\HasAttributesContract;

/**
 * EntityContract
 *
 * @method setAttribute(string $key, mixed $value): static
 * @method setAttributes(array $attribute): static
 */
interface EntityContract extends HasAttributesContract
{
    /**
     * Get the entity's ID.
     *
     * @return mixed
     */
    public function getId(): mixed;

    /**
     * Get the entity's created at timestamp.
     *
     * @return Carbon|null
     */
    public function getCreatedAt(): ?Carbon;

    /**
     * Get the entity's updated at timestamp.
     *
     * @return Carbon|null
     */
    public function getUpdatedAt(): ?Carbon;

    /**
     * Create an entity from an array of data.
     *
     * @param array $data
     * @param bool $asDirty
     * @return static
     */
    public static function fromArray(array $data, $asDirty = false): self;

    /**
     * Determine if the entity is new.
     *
     * @return bool
     */
    public function isNew(): bool;

    /**
     * Convert the entity to an array.
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Get the entity's attributes.
     *
     * @return array
     */
    public function getAttributes(): array;
}
