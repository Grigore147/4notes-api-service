<?php

declare(strict_types=1);

namespace App\Domains\Note\Domain\Entities;

use Carbon\Carbon;
use Ramsey\Uuid\UuidInterface;
use App\Core\Support\Undefined;
use App\Core\Domain\Entities\Entity;
use App\Domains\Note\Domain\Entities\Contracts\SpaceEntityContract;

final class Space extends Entity implements SpaceEntityContract
{
    public function __construct(
        ?UuidInterface $id = null,
        ?UuidInterface $userId = null,
        string $name = '',
        string $description = '',
        ?Carbon $createdAt = null,
        ?Carbon $updatedAt = null
    ) {
        $this->initAttributes(get_defined_vars());
    }

    public function getUserId(): UuidInterface
    {
        return $this->getAttribute('userId');
    }

    public function getName(): string
    {
        return $this->getAttribute('name');
    }

    public function getDescription(): string
    {
        return $this->getAttribute('description');
    }

    public function set(
        Undefined|UuidInterface $userId = new Undefined,
        Undefined|string $name = new Undefined,
        Undefined|string $description = new Undefined,
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
