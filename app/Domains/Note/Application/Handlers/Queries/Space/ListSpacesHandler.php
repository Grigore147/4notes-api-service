<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Handlers\Queries\Space;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Domains\Note\Application\Queries\Space\ListSpaces;
use App\Domains\Note\Domain\Repositories\SpacesRepository as SpacesRepositoryContract;
use App\Domains\Note\Infrastructure\Repositories\SpacesRepository;

final class ListSpacesHandler
{
    /**
     * ListSpacesHandler constructor.
     *
     * @param SpacesRepository $spacesRepository
     */
    public function __construct(
        private SpacesRepositoryContract $spacesRepository
    ) {}

    public function handle(ListSpaces $query): Collection|LengthAwarePaginator
    {
        return $this->spacesRepository->find($query->filters);
    }
}
