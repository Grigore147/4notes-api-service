<?php

declare(strict_types=1);

namespace App\Domains\Note\Domain\Services;

use Ramsey\Uuid\UuidInterface;
use Illuminate\Support\Collection;
use App\Domains\Note\Domain\Repositories\NotebooksRepository;
use App\Domains\Note\Domain\Entities\Contracts\NotebookEntityContract;
use App\Domains\Note\Domain\Services\Contracts\NotebookService as NotebookServiceContract;

/**
 * Notebook Domain Service
 *
 * This service is responsible for handling the domain / business logic for the Notebook domain.
 */
final class NotebookService implements NotebookServiceContract
{
    public function __construct(
        protected NotebooksRepository $notebooksRepository
    ){}

    public function create(NotebookEntityContract|array $notebook): NotebookEntityContract
    {
        return $this->notebooksRepository->create($notebook);
    }

    public function update(string|UuidInterface|NotebookEntityContract $notebook, array $data = []): NotebookEntityContract
    {
        return $this->notebooksRepository->update($notebook, $data);
    }

    public function delete(string|UuidInterface|NotebookEntityContract $notebook): bool
    {
        return $this->notebooksRepository->delete($notebook);
    }

    public function deleteNotebooks(Collection|array|int|string $ids): bool
    {
        return $this->notebooksRepository->deleteMany($ids);
    }
}
