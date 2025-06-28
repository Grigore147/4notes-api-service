<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Validation\Rules;

use App\Core\Application\Validation\Rules\ResourceId;
use App\Domains\Note\Domain\Repositories\NotebooksRepository;

final class NotebookId extends ResourceId
{
    public const REPOSITORY = NotebooksRepository::class;
}
