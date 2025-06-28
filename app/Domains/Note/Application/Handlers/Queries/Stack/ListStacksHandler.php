<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Handlers\Queries\Stack;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Domains\Note\Application\Queries\Stack\ListStacks;
use App\Domains\Note\Domain\Repositories\StacksRepository as StacksRepositoryContract;
use App\Domains\Note\Infrastructure\Repositories\StacksRepository;

final class ListStacksHandler
{
    /**
     * ListStacksHandler constructor.
     *
     * @param StacksRepository $stacksRepository
     */
    public function __construct(
        private StacksRepositoryContract $stacksRepository
    ) {}

    public function handle(ListStacks $query): Collection|LengthAwarePaginator
    {
        return $this->stacksRepository->find($query->filters);
    }
}
