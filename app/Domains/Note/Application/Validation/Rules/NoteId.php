<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Validation\Rules;

use App\Core\Application\Validation\Rules\ResourceId;
use App\Domains\Note\Domain\Repositories\NotesRepository;

final class NoteId extends ResourceId
{
    public const REPOSITORY = NotesRepository::class;
}
