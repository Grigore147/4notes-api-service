<?php

declare(strict_types=1);

namespace App\Domains\Note\Domain\Entities;

use Carbon\Carbon;
use Ramsey\Uuid\UuidInterface;
use App\Core\Domain\Entities\Entity;
use App\Core\Support\Undefined;
use App\Domains\Note\Domain\Entities\Contracts\StackEntityContract;

final class Stack extends Entity implements StackEntityContract
{
    public function __construct(
        UuidInterface|string|null $id = null,
        UuidInterface|string|null $userId = null,
        UuidInterface|string|null $spaceId = null,
        mixed $space = null,
        string $name = '',
        ?Carbon $createdAt = null,
        ?Carbon $updatedAt = null
    ) {
        $this->initAttributes(get_defined_vars());
    }

    public function getUserId(): UuidInterface|string
    {
        return $this->getAttribute('userId');
    }

    public function getSpaceId(): UuidInterface|string|null
    {
        return $this->getAttribute('spaceId');
    }

    public function getSpace(): mixed
    {
        return $this->getAttribute('space');
    }

    public function getName(): string
    {
        return $this->getAttribute('name');
    }

    public function set(
        Undefined|UuidInterface|string|null $userId = new Undefined,
        Undefined|UuidInterface|string|null $spaceId = new Undefined,
        Undefined|string $name = new Undefined,
        Undefined|Carbon $createdAt = new Undefined,
        Undefined|Carbon $updatedAt = new Undefined
    ): self
    {
        foreach(get_defined_vars() as $key => $value) {
            if (!($value instanceof Undefined)) {
                $this->setAttribute($key, $value);
            }
        }

        return $this;
    }
}
