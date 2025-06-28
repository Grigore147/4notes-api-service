<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Handlers\Queries\Notebook;

use App\Domains\Note\Application\Queries\Notebook\GetNotebookById;
use App\Domains\Note\Infrastructure\Repositories\NotebooksRepository;
use App\Domains\Note\Domain\Repositories\NotebooksRepository as NotebooksRepositoryContract;
use App\Domains\Note\Domain\Entities\Contracts\NotebookEntityContract;

final class GetNotebookByIdHandler
{
    /**
     * GetNotebookByIdHandler constructor.
     *
     * @param NotebooksRepository $notebooksRepository
     */
    public function __construct(
        private NotebooksRepositoryContract $notebooksRepository
    ) {}

    public function handle(GetNotebookById $query): NotebookEntityContract
    {
        return $this->notebooksRepository->getById($query->id);
    }
}
