<?php

declare(strict_types=1);

namespace App\Domains\Note\Domain\Services\Contracts;

use Ramsey\Uuid\UuidInterface;
use Illuminate\Support\Collection;
use App\Domains\Note\Domain\Entities\Contracts\SpaceEntityContract;

interface SpaceService
{
    /**
     * Create Space
     *
     * @param SpaceEntityContract|array $entity
     * @return SpaceEntityContract
     */
    public function create(SpaceEntityContract|array $space): SpaceEntityContract;

    /**
     * Update Space
     *
     * @param string|UuidInterface|SpaceEntityContract $entity
     * @param array $data
     * @return SpaceEntityContract
     */
    public function update(string|UuidInterface|SpaceEntityContract $space, array $data = []): SpaceEntityContract;

    /**
     * Delete Space
     *
     * @param string|UuidInterface|SpaceEntityContract $id
     * @return bool
     */
    public function delete(string|UuidInterface|SpaceEntityContract $space): bool;

    /**
     * Delete multiple Spaces
     *
     * @param Collection|array|int|string $ids
     * @return bool True if all spaces were deleted
     */
    public function deleteSpaces(Collection|array|int|string $ids): bool;
}
