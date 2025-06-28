<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Services\Contracts;

use Ramsey\Uuid\UuidInterface;
use Illuminate\Support\Collection;
use App\Domains\Note\Domain\Entities\Contracts\SpaceEntityContract;

interface SpaceServiceContract
{
    public function create(SpaceEntityContract|array $space): SpaceEntityContract;

    public function update(string|UuidInterface|SpaceEntityContract $space, array $data = []): SpaceEntityContract;

    public function delete(string|UuidInterface|SpaceEntityContract $space): bool;

    public function deleteSpaces(Collection|array|string $ids): bool;
}
