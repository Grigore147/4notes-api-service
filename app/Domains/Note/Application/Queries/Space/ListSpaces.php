<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Queries\Space;

use App\Core\Application\QueryBus\FilterableQuery;
use App\Core\Application\QueryBus\Query;

final class ListSpaces extends Query
{
    use FilterableQuery;
}
