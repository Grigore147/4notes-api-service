<?php

declare(strict_types=1);

namespace App\Domains\Note\Presentation\Resources;

use App\Core\Presentation\Resources\ResourceCollection;
use App\Domains\Note\Presentation\Resources\SpaceResource;

final class SpaceCollection extends ResourceCollection
{
    public $collects = SpaceResource::class;
}
