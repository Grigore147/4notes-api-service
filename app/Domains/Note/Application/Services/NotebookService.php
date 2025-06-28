<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Services;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Domains\Note\Application\Services\Contracts\NotebookServiceContract;
use App\Domains\Note\Domain\Entities\Notebook;
use App\Domains\Note\Domain\Entities\Contracts\NotebookEntityContract;
use App\Domains\Note\Domain\Events\Notebook\NotebookCreated;
use App\Domains\Note\Domain\Events\Notebook\NotebookDeleted;
use App\Domains\Note\Domain\Events\Notebook\NotebookUpdated;
use App\Domains\Note\Domain\Repositories\NotebooksRepository;
use App\Domains\Note\Domain\Services\NotebookService as NotebookDomainService;

final class NotebookService implements NotebookServiceContract
{
    public function __construct(
        protected NotebookDomainService $notebookService,
        protected NotebooksRepository $notebooksRepository
    ){}

    public function create(NotebookEntityContract|array $notebook): NotebookEntityContract
    {
        return DB::transaction(function () use ($notebook): NotebookEntityContract {
            /** @var NotebookEntityContract $notebook */
            $notebook = $this->notebookService->create($notebook);

            NotebookCreated::dispatch($notebook);

            return $notebook;
        });
    }

    public function update(string|UuidInterface|NotebookEntityContract $notebook, array $data = []): NotebookEntityContract
    {
        return DB::transaction(function () use ($notebook, $data): NotebookEntityContract {
            if (!($notebook instanceof NotebookEntityContract)) {
                $notebook = $this->notebooksRepository->findById($notebook);
            }

            $this->notebookService->update($notebook, $data);

            NotebookUpdated::dispatch($notebook);

            return $notebook;
        });
    }

    public function delete(string|UuidInterface|NotebookEntityContract $notebook): bool
    {
        return DB::transaction(function () use ($notebook): bool {
            if (!($notebook instanceof NotebookEntityContract)) {
                $notebook = new Notebook(id: is_string($notebook) ? Uuid::fromString($notebook) : $notebook);
            }

            if ($deleted = $this->notebookService->delete($notebook)) {
                NotebookDeleted::dispatch($notebook);
            }

            return $deleted;
        });
    }

    public function deleteNotebooks(Collection|array|string $ids): bool
    {
        return DB::transaction(function () use ($ids): bool {
            if ($deleted = $this->notebookService->deleteNotebooks($ids)) {
                if ($ids instanceof Collection) {
                    $ids = $ids->toArray();
                } elseif (is_string($ids)) {
                    $ids = [$ids];
                }

                foreach ($ids as $id) {
                    NotebookDeleted::dispatch(new Notebook(
                        id: is_string($id) ? Uuid::fromString($id) : $id)
                    );
                }
            }

            return $deleted;
        });
    }
}
