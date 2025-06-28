<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Handlers\Queries\Stack;

use App\Domains\Note\Application\Queries\Stack\FindStackById;
use App\Domains\Note\Infrastructure\Repositories\StacksRepository;
use App\Domains\Note\Domain\Repositories\StacksRepository as StacksRepositoryContract;
use App\Domains\Note\Domain\Entities\Contracts\StackEntityContract;

final class FindStackByIdHandler
{
    /**
     * FindStackByIdHandler constructor.
     *
     * @param StacksRepository $stacksRepository
     */
    public function __construct(
        private StacksRepositoryContract $stacksRepository
    ) {}

    public function handle(FindStackById $query): ?StackEntityContract
    {
        return $this->stacksRepository->findById($query->id);
    }
}
