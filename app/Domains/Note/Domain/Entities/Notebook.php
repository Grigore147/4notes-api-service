<?php

declare(strict_types=1);

namespace App\Domains\Note\Domain\Entities;

use Carbon\Carbon;
use Ramsey\Uuid\UuidInterface;
use App\Core\Support\Undefined;
use App\Core\Domain\Entities\Entity;
use App\Domains\Note\Domain\Entities\Contracts\NotebookEntityContract;

final class Notebook extends Entity implements NotebookEntityContract
{
    public function __construct(
        UuidInterface|string|null $id = null,
        UuidInterface|string|null $userId = null,
        UuidInterface|string|null $spaceId = null,
        mixed $space = null,
        UuidInterface|string|null $stackId = null,
        mixed $stack = null,
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

    public function getStackId(): UuidInterface|string|null
    {
        return $this->getAttribute('stackId');
    }

    public function getStack(): mixed
    {
        return $this->getAttribute('stack');
    }

    public function getName(): String
    {
        return $this->getAttribute('name');
    }

    public function set(
        Undefined|UuidInterface|string|null $userId = new Undefined,
        Undefined|UuidInterface|string|null $spaceId = new Undefined,
        Undefined|UuidInterface|string|null $stackId = new Undefined,
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
