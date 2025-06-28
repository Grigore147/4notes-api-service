<?php

declare(strict_types=1);

namespace App\Core\Application\QueryBus;

use App\Core\Application\QueryBus\Contracts\QueryContract;
use App\Core\Application\QueryBus\Contracts\QueryHandlerContract;
use App\Core\Application\QueryBus\Contracts\QueryBus as QueryBusContract;

/**
 * QueryBus
 *
 * @package App\Core\Application\QueryBus
 */
final class QueryBus implements QueryBusContract
{
    /**
     * @var array <class-string<QueryContract>, class-string<QueryHandler>>
     */
    protected array $map = [];

    /**
     * Map the query to the handler
     *
     * @param array <class-string<QueryContract>, class-string<QueryHandler>> $map
     * @return self
     */
    public function map(array $map): self
    {
        $this->map = $map;

        return $this;
    }

    /**
     * Handle the query
     *
     * @param QueryContract $query
     * @return mixed
     */
    public function handle(QueryContract $query): mixed
    {
        /** @var QueryHandlerContract $handler */
        $handler = app($this->map[$query::class]);

        return $handler->handle($query);
    }

    /**
     * Alias for dispatch method
     *
     * @param QueryContract $query
     * @return mixed
     */
    public function query(QueryContract $query): mixed
    {
        return $this->dispatch($query);
    }

    /**
     * Dispatch the query
     *
     * @param QueryContract $query
     * @return mixed
     */
    public static function dispatch(QueryContract $query): mixed
    {
        return app(QueryBusContract::class)->dispatch($query);
    }
}
