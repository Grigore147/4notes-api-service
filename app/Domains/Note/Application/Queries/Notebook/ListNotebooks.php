<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Queries\Notebook;

use App\Core\Application\QueryBus\FilterableQuery;
use App\Core\Application\QueryBus\Query;

final class ListNotebooks extends Query
{
    use FilterableQuery;
}
