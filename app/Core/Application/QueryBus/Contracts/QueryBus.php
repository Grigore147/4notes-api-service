<?php

declare(strict_types=1);

namespace App\Core\Application\QueryBus\Contracts;

use App\Core\Application\QueryBus\Contracts\QueryContract;

interface QueryBus
{
    /**
     * Map the query to the handler
     *
     * @param array <class-string<QueryContract>, class-string<QueryHandler>> $map
     * @return self
     */
    public function map(array $map): self;

    /**
     * Handle the query
     *
     * @param QueryContract $query
     * @return mixed
     */
    public function handle(QueryContract $query): mixed;

    /**
     * Alias for handle method
     *
     * @param QueryContract $query
     * @return mixed
     */
    public function query(QueryContract $query): mixed;

    /**
     * Dispatch the query
     *
     * @param QueryContract $query
     * @return mixed
     */
    public static function dispatch(QueryContract $query): mixed;
}
