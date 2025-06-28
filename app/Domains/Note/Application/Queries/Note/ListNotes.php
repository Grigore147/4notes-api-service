<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Queries\Note;

use App\Core\Application\QueryBus\FilterableQuery;
use App\Core\Application\QueryBus\Query;

final class ListNotes extends Query
{
    use FilterableQuery;
}
