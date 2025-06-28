<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Handlers\Queries\Space;

use App\Domains\Note\Application\Queries\Space\FindSpaceById;
use App\Domains\Note\Infrastructure\Repositories\SpacesRepository;
use App\Domains\Note\Domain\Repositories\SpacesRepository as SpacesRepositoryContract;
use App\Domains\Note\Domain\Entities\Contracts\SpaceEntityContract;

final class FindSpaceByIdHandler
{
    /**
     * FindSpaceByIdHandler constructor.
     *
     * @param SpacesRepository $spacesRepository
     */
    public function __construct(
        private SpacesRepositoryContract $spacesRepository
    ) {}

    public function handle(FindSpaceById $query): ?SpaceEntityContract
    {
        return $this->spacesRepository->findById($query->id);
    }
}
