<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Handlers\Queries\Notebook;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Domains\Note\Application\Queries\Notebook\ListNotebooks;
use App\Domains\Note\Domain\Repositories\NotebooksRepository as NotebooksRepositoryContract;
use App\Domains\Note\Infrastructure\Repositories\NotebooksRepository;

final class ListNotebooksHandler
{
    /**
     * ListNotebooksHandler constructor.
     *
     * @param NotebooksRepository $notebooksRepository
     */
    public function __construct(
        private NotebooksRepositoryContract $notebooksRepository
    ) {}

    public function handle(ListNotebooks $query): Collection|LengthAwarePaginator
    {
        return $this->notebooksRepository->find($query->filters);
    }
}
