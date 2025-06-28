<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Database\Eloquent\Builders;

use Illuminate\Support\Facades\Pipeline;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use App\Core\Infrastructure\Database\Concerns\QueryFilter;
use App\Core\Infrastructure\Database\Concerns\OrderingBuilder;
use App\Core\Infrastructure\Database\Concerns\FilteringBuilder;
use App\Core\Infrastructure\Database\Concerns\IncludingBuilder;
use App\Core\Infrastructure\Database\Concerns\SelectingBuilder;
use App\Core\Infrastructure\Database\Concerns\PaginationBuilder;

abstract class Builder extends EloquentBuilder // @pest-arch-ignore-line
{
    /**
     * The default query limit.
     *
     * @var int
     */
    public const QUERY_LIMIT_DEFAULT = 25;

    /**
     * The maximum query limit.
     *
     * @var int
     */
    public const QUERY_LIMIT_MAX = 1000;

    /**
     * The query filters that are available.
     *
     * @var array<string, string>
     */
    public const QUERY_BUILDERS = [
        'selecting'  => SelectingBuilder::class,
        'filtering'  => FilteringBuilder::class,
        'including'  => IncludingBuilder::class,
        'ordering'   => OrderingBuilder::class,
        'pagination' => PaginationBuilder::class
    ];

    /**
     * The pagination offset.
     *
     * @var int
     */
    protected int $paginationOffset = 0;
    
    /**
     * The pagination limit.
     *
     * @var int
     */
    protected int $paginationLimit = self::QUERY_LIMIT_DEFAULT;

    /**
     * Apply filters to the query builder.
     *
     * @param array<string, mixed> $parameters
     * @return self
     */
    public function filter(array $parameters = []): self
    {
        return Pipeline::send(new QueryFilter($this, $parameters))
            ->through($this->getAllowedQueryFilters())
            ->then(fn (QueryFilter $queryFilter) => $queryFilter->builder);
    }

    /**
     * Get the allowed query filters for the model.
     *
     * @return array<string>
     */
    public function getAllowedQueryFilters(): array
    {
        return array_map(function ($filter) {
            return self::QUERY_BUILDERS[$filter] ?? $filter;
        }, $this->model->allowedFilters);
    }

    /**
     * Set the pagination for the query.
     *
     * @param int $offset
     * @param int $limit
     * @return self
     */
    public function setPagination(?int $offset = null, ?int $limit = null): self
    {
        $this->paginationOffset = $offset ?? 0;
        $this->paginationLimit = $limit ?? self::QUERY_LIMIT_DEFAULT;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null, $total = null)
    {
        if ($page === null) {
            $page = ceil($this->paginationOffset / $this->paginationLimit) + 1;
        }

        return parent::paginate($this->paginationLimit, $columns, $pageName, $page, $total);
    }
}
