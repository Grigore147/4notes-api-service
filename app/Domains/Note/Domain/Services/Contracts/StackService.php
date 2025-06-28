<?php

declare(strict_types=1);

namespace App\Domains\Note\Domain\Services\Contracts;

use Ramsey\Uuid\UuidInterface;
use Illuminate\Support\Collection;
use App\Domains\Note\Domain\Entities\Contracts\StackEntityContract;

interface StackService
{
    /**
     * Create Stack
     *
     * @param StackEntityContract|array $entity
     * @return StackEntityContract
     */
    public function create(StackEntityContract|array $stack): StackEntityContract;

    /**
     * Update Stack
     *
     * @param string|UuidInterface|StackEntityContract $entity
     * @param array $data
     * @return StackEntityContract
     */
    public function update(string|UuidInterface|StackEntityContract $stack, array $data = []): StackEntityContract;

    /**
     * Delete Stack
     *
     * @param string|UuidInterface|StackEntityContract $id
     * @return bool
     */
    public function delete(string|UuidInterface|StackEntityContract $stack): bool;

    /**
     * Delete multiple Stacks
     *
     * @param Collection|array|int|string $ids
     * @return bool True if all stacks were deleted
     */
    public function deleteStacks(Collection|array|int|string $ids): bool;
}
