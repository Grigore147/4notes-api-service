<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Validation\Rules;

use App\Core\Application\Validation\Rules\ResourceId;
use App\Domains\Note\Domain\Repositories\SpacesRepository;

final class SpaceId extends ResourceId
{
    public const REPOSITORY = SpacesRepository::class;
}
