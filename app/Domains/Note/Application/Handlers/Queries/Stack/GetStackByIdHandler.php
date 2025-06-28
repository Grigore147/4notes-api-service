<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Handlers\Queries\Stack;

use App\Domains\Note\Application\Queries\Stack\GetStackById;
use App\Domains\Note\Infrastructure\Repositories\StacksRepository;
use App\Domains\Note\Domain\Repositories\StacksRepository as StacksRepositoryContract;
use App\Domains\Note\Domain\Entities\Contracts\StackEntityContract;

final class GetStackByIdHandler
{
    /**
     * GetStackByIdHandler constructor.
     *
     * @param StacksRepository $stacksRepository
     */
    public function __construct(
        private StacksRepositoryContract $stacksRepository
    ) {}

    public function handle(GetStackById $query): StackEntityContract
    {
        return $this->stacksRepository->getById($query->id);
    }
}
