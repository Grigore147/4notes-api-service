<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Services;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Domains\Note\Application\Services\Contracts\StackServiceContract;
use App\Domains\Note\Domain\Entities\Stack;
use App\Domains\Note\Domain\Entities\Contracts\StackEntityContract;
use App\Domains\Note\Domain\Events\Stack\StackCreated;
use App\Domains\Note\Domain\Events\Stack\StackDeleted;
use App\Domains\Note\Domain\Events\Stack\StackUpdated;
use App\Domains\Note\Domain\Repositories\StacksRepository;
use App\Domains\Note\Domain\Services\StackService as StackDomainService;

final class StackService implements StackServiceContract
{
    public function __construct(
        protected StackDomainService $stackService,
        protected StacksRepository $stacksRepository
    ){}

    public function create(StackEntityContract|array $stack): StackEntityContract
    {
        return DB::transaction(function () use ($stack): StackEntityContract {
            /** @var StackEntityContract $stack */
            $stack = $this->stackService->create($stack);

            StackCreated::dispatch($stack);

            return $stack;
        });
    }

    public function update(string|UuidInterface|StackEntityContract $stack, array $data = []): StackEntityContract
    {
        return DB::transaction(function () use ($stack, $data): StackEntityContract {
            if (!($stack instanceof StackEntityContract)) {
                $stack = $this->stacksRepository->findById($stack);
            }

            $this->stackService->update($stack, $data);

            StackUpdated::dispatch($stack);

            return $stack;
        });
    }

    public function delete(string|UuidInterface|StackEntityContract $stack): bool
    {
        return DB::transaction(function () use ($stack): bool {
            if (!($stack instanceof StackEntityContract)) {
                $stack = new Stack(id: is_string($stack) ? Uuid::fromString($stack) : $stack);
            }

            if ($deleted = $this->stackService->delete($stack)) {
                StackDeleted::dispatch($stack);
            }

            return $deleted;
        });
    }

    public function deleteStacks(Collection|array|string $ids): bool
    {
        return DB::transaction(function () use ($ids): bool {
            if ($deleted = $this->stackService->deleteStacks($ids)) {
                if ($ids instanceof Collection) {
                    $ids = $ids->toArray();
                } elseif (is_string($ids)) {
                    $ids = [$ids];
                }

                foreach ($ids as $id) {
                    StackDeleted::dispatch(new Stack(
                        id: is_string(value: $id) ? Uuid::fromString($id) : $id)
                    );
                }
            }

            return $deleted;
        });
    }
}
