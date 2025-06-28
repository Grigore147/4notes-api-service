<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Services\Contracts;

use Ramsey\Uuid\UuidInterface;
use Illuminate\Support\Collection;
use App\Domains\Note\Domain\Entities\Contracts\StackEntityContract;

interface StackServiceContract
{
    public function create(StackEntityContract|array $stack): StackEntityContract;

    public function update(string|UuidInterface|StackEntityContract $stack, array $data = []): StackEntityContract;

    public function delete(string|UuidInterface|StackEntityContract $stack): bool;

    public function deleteStacks(Collection|array|string $ids): bool;
}
