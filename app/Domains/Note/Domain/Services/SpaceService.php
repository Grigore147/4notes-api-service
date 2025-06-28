<?php

declare(strict_types=1);

namespace App\Domains\Note\Domain\Services;

use Ramsey\Uuid\UuidInterface;
use Illuminate\Support\Collection;
use App\Domains\Note\Domain\Repositories\SpacesRepository;
use App\Domains\Note\Domain\Entities\Contracts\SpaceEntityContract;
use App\Domains\Note\Domain\Services\Contracts\SpaceService as SpaceServiceContract;

/**
 * Space Domain Service
 *
 * This service is responsible for handling the domain / business logic for the Space domain.
 */
final class SpaceService implements SpaceServiceContract
{
    public function __construct(
        protected SpacesRepository $spacesRepository
    ){}

    public function create(SpaceEntityContract|array $space): SpaceEntityContract
    {
        return $this->spacesRepository->create($space);
    }

    public function update(string|UuidInterface|SpaceEntityContract $space, array $data = []): SpaceEntityContract
    {
        return $this->spacesRepository->update($space, $data);
    }

    public function delete(string|UuidInterface|SpaceEntityContract $space): bool
    {
        return $this->spacesRepository->delete($space);
    }

    public function deleteSpaces(Collection|array|int|string $ids): bool
    {
        return $this->spacesRepository->deleteMany($ids);
    }
}
