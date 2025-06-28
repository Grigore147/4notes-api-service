<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Services\Contracts;

use Ramsey\Uuid\UuidInterface;
use Illuminate\Support\Collection;
use App\Domains\Note\Domain\Entities\Contracts\NotebookEntityContract;

interface NotebookServiceContract
{
    public function create(NotebookEntityContract|array $notebook): NotebookEntityContract;

    public function update(string|UuidInterface|NotebookEntityContract $notebook, array $data = []): NotebookEntityContract;

    public function delete(string|UuidInterface|NotebookEntityContract $notebook): bool;

    public function deleteNotebooks(Collection|array|string $ids): bool;
}
