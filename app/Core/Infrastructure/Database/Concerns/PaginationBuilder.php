<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Database\Concerns;

use Closure;
use Illuminate\Support\Arr;
use App\Core\Infrastructure\Database\Concerns\QueryFilter;
use App\Core\Infrastructure\Database\Eloquent\Builders\Builder;

final class PaginationBuilder
{
    /**
     * Handle the query.
     *
     * @param QueryFilter $queryFilter
     */
    public function handle(QueryFilter $queryFilter, Closure $next)
    {
        $pagination = $this->parsePagination($queryFilter->parameters);

        $queryFilter->builder->setPagination($pagination['offset'], $pagination['limit']);

        return $next($queryFilter);
    }

    /**
     * Parse the pagination parameter.
     *
     * @param array $parameters
     * @return array
     */
    public function parsePagination(array $parameters): array
    {
        $pagination = [
            'offset' => (int)Arr::get($parameters, 'offset', 0),
            'limit'  => (int)Arr::get($parameters, 'limit', 0),
        ];

        if ($pagination['offset'] < 0) {
            $pagination['offset'] = 0;
        }
        if ($pagination['limit'] > Builder::QUERY_LIMIT_MAX) {
            $pagination['limit'] = Builder::QUERY_LIMIT_MAX;
        }
        if ($pagination['limit'] <= 0) {
            $pagination['limit'] = Builder::QUERY_LIMIT_DEFAULT;
        }

        if (Arr::has($parameters, 'page')) {
            $page = (int)$parameters['page'];

            if ($page < 1) { $page = 1; }

            $pagination['offset'] = ($page - 1) * $pagination['limit'];
        }

        return $pagination;
    }
}
