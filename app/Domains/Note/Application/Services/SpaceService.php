<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Services;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Domains\Note\Application\Services\Contracts\SpaceServiceContract;
use App\Domains\Note\Domain\Entities\Space;
use App\Domains\Note\Domain\Entities\Contracts\SpaceEntityContract;
use App\Domains\Note\Domain\Events\Space\SpaceCreated;
use App\Domains\Note\Domain\Events\Space\SpaceDeleted;
use App\Domains\Note\Domain\Events\Space\SpaceUpdated;
use App\Domains\Note\Domain\Repositories\SpacesRepository;
use App\Domains\Note\Domain\Services\SpaceService as SpaceDomainService;

final class SpaceService implements SpaceServiceContract
{
    public function __construct(
        protected SpaceDomainService $spaceService,
        protected SpacesRepository $spacesRepository
    ){}

    public function create(SpaceEntityContract|array $space): SpaceEntityContract
    {
        return DB::transaction(function () use ($space): SpaceEntityContract {
            /** @var SpaceEntityContract $space */
            $space = $this->spaceService->create($space);

            SpaceCreated::dispatch($space);

            return $space;
        });
    }

    public function update(string|UuidInterface|SpaceEntityContract $space, array $data = []): SpaceEntityContract
    {
        return DB::transaction(function () use ($space, $data): SpaceEntityContract {
            if (!($space instanceof SpaceEntityContract)) {
                $space = $this->spacesRepository->findById($space);
            }

            $this->spaceService->update($space, $data);

            SpaceUpdated::dispatch($space);

            return $space;
        });
    }

    public function delete(string|UuidInterface|SpaceEntityContract $space): bool
    {
        return DB::transaction(function () use ($space): bool {
            if (!($space instanceof SpaceEntityContract)) {
                $space = new Space(id: is_string($space) ? Uuid::fromString($space) : $space);
            }

            if ($deleted = $this->spaceService->delete($space)) {
                SpaceDeleted::dispatch($space);
            }

            return $deleted;
        });
    }

    public function deleteSpaces(Collection|array|string $ids): bool
    {
        return DB::transaction(function () use ($ids): bool {
            if ($deleted = $this->spaceService->deleteSpaces($ids)) {
                if ($ids instanceof Collection) {
                    $ids = $ids->toArray();
                } elseif (is_string($ids)) {
                    $ids = [$ids];
                }

                foreach ($ids as $id) {
                    SpaceDeleted::dispatch(new Space(
                        id: is_string($id) ? Uuid::fromString($id) : $id)
                    );
                }
            }

            return $deleted;
        });
    }
}
