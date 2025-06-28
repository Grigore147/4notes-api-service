<?php

declare(strict_types=1);

namespace App\Domains\Note\Presentation\Resources;

use App\Core\Presentation\Resources\ResourceCollection;
use App\Domains\Note\Presentation\Resources\StackResource;

final class StackCollection extends ResourceCollection
{
    public $collects = StackResource::class;
}
