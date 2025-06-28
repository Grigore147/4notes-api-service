<?php

declare(strict_types=1);

namespace App\Domains\Note\Domain\Services\Contracts;

use Ramsey\Uuid\UuidInterface;
use Illuminate\Support\Collection;
use App\Domains\Note\Domain\Entities\Contracts\NotebookEntityContract;

interface NotebookService
{
    /**
     * Create Notebook
     *
     * @param NotebookEntityContract|array $entity
     * @return NotebookEntityContract
     */
    public function create(NotebookEntityContract|array $notebook): NotebookEntityContract;

    /**
     * Update Notebook
     *
     * @param string|UuidInterface|NotebookEntityContract $entity
     * @param array $data
     * @return NotebookEntityContract
     */
    public function update(string|UuidInterface|NotebookEntityContract $notebook, array $data = []): NotebookEntityContract;

    /**
     * Delete Notebook
     *
     * @param string|UuidInterface|NotebookEntityContract $id
     * @return bool
     */
    public function delete(string|UuidInterface|NotebookEntityContract $notebook): bool;

    /**
     * Delete multiple Notebooks
     *
     * @param Collection|array|int|string $ids
     * @return bool True if all notebooks were deleted
     */
    public function deleteNotebooks(Collection|array|int|string $ids): bool;
}
