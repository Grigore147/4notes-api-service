<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Queries\Stack;

use App\Core\Application\QueryBus\FilterableQuery;
use App\Core\Application\QueryBus\Query;

final class ListStacks extends Query
{
    use FilterableQuery;
}
