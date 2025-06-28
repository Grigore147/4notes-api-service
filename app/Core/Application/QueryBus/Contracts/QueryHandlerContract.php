<?php

declare(strict_types=1);

namespace App\Core\Application\QueryBus\Contracts;

use App\Core\Application\QueryBus\Contracts\QueryContract;

interface QueryHandlerContract
{
    /**
     * Handle the Query
     *
     * @param QueryContract $query
     * @return mixed
     */
    public function handle(QueryContract $query): mixed;
}
