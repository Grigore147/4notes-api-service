<?php

declare(strict_types=1);

namespace App\Domains\Note\Presentation\Resources;

use App\Core\Presentation\Resources\ResourceCollection;
use App\Domains\Note\Presentation\Resources\NotebookResource;

final class NotebookCollection extends ResourceCollection
{
    public $collects = NotebookResource::class;
}
