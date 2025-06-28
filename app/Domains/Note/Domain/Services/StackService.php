<?php

declare(strict_types=1);

namespace App\Domains\Note\Domain\Services;

use Ramsey\Uuid\UuidInterface;
use Illuminate\Support\Collection;
use App\Domains\Note\Domain\Repositories\StacksRepository;
use App\Domains\Note\Domain\Entities\Contracts\StackEntityContract;
use App\Domains\Note\Domain\Services\Contracts\StackService as StackServiceContract;

/**
 * Stack Domain Service
 *
 * This service is responsible for handling the domain / business logic for the Stack domain.
 *
 * @package App\Domains\Stacks\Domain\Services
 */
final class StackService implements StackServiceContract
{
    public function __construct(
        protected StacksRepository $stacksRepository
    ){}

    public function create(StackEntityContract|array $stack): StackEntityContract
    {
        return $this->stacksRepository->create($stack);
    }

    public function update(string|UuidInterface|StackEntityContract $stack, array $data = []): StackEntityContract
    {
        return $this->stacksRepository->update($stack, $data);
    }

    public function delete(string|UuidInterface|StackEntityContract $stack): bool
    {
        return $this->stacksRepository->delete($stack);
    }

    public function deleteStacks(Collection|array|int|string $ids): bool
    {
        return $this->stacksRepository->deleteMany($ids);
    }
}
