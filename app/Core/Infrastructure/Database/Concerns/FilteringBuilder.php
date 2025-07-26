<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Database\Concerns;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use App\Core\Infrastructure\Database\Concerns\QueryFilter;
use App\Core\Infrastructure\Database\Eloquent\Models\BaseModel;

final class FilteringBuilder
{
    /**
     * Apply filtering to the query.
     *
     * @param QueryFilter $queryFilter
     */
    public function handle(QueryFilter $queryFilter, Closure $next): Builder
    {
        /** @var BaseModel $model */
        $model = $queryFilter->builder->getModel();
        $table = $model->getTable();
        $aliasAttributes = $model->getAliasAttributes();
        $filterableAttributes = $model->getFilterableAttributes();

        $operators = [
            'is'  => '=',
            'not' => '!=',
            'lt'  => '<',
            'lte' => '<=',
            'gt'  => '>',
            'gte' => '>=',
        ];

        foreach ($this->parseFilters($queryFilter->parameters) as $filter) {
            [$attr, $operator, $value] = $filter;

            $attr = $aliasAttributes[$attr] ?? $attr;
            $method = 'filter'.Str::studly(str_replace('.', '_', $attr));
            
            if (method_exists($queryFilter->builder, $method)) {
                $queryFilter->builder->{$method}($value, $queryFilter);

                continue;
            }
            if (method_exists($model, $method)) {
                $model->{$method}($queryFilter->builder, $value, $queryFilter);

                continue;
            }
            
            if (!in_array($attr, $filterableAttributes, true)) {
                continue;
            }

            if ($operator === 'in' || $operator === 'notin') {
                $queryFilter->builder->{$operator === 'in' ? 'whereIn' : 'whereNotIn'}($attr, $value);
            } elseif ($operator === 'like' || $operator === 'notlike') {
                $queryFilter->builder->where(
                    $table .'.'. $attr,
                    ($operator === 'like' ? 'like' : 'not like'),
                    '%'. $value .'%'
                );
            } elseif ($operator === 'start' || $operator === 'end') {
                $queryFilter->builder->where(
                    $table .'.'. $attr,
                    'like',
                    $operator === 'start' ? $value.'%' : '%'.$value
                );
            } elseif ($operator === 'null') {
                $queryFilter->builder->whereNull($table .'.'. $attr);
            } elseif (isset($operators[$operator])) {
                $queryFilter->builder->where($table .'.'. $attr, $operators[$operator], $value);
            }
        }

        $this->applySearch($queryFilter);

        return $next($queryFilter);
    }

    public function parseFilters(array $parameters): array
    {
        $blackListedFilters = [
            'sort',
            'page',
            'include',
            'search',
            'limit',
            'offset',
            'fields',
            'q',
        ];
        $operatorsAliases = [
            'eq' => 'is',
            'ne' => 'not',
            'q'  => 'like'
        ];

        $filters = [];

        foreach ($parameters as $attr => $value) {
            if (in_array($attr, $blackListedFilters, true)) {
                continue;
            }

            $operator = 'is';

            if (is_array($value)) {
                $operator = 'in';
            }

            if (is_string($value) && preg_match(
                    '/^(eq|is|ne|not|lt|lte|gt|gte|in|notin|q|like|notlike|start|end|null):((.+)?)$/i',
                    $value,
                    $exp
                )) {
                $value = $exp[1];

                // If operator is present
                if (count($exp) >= 3) {
                    [, $operator, $value] = $exp;
                }

                // Alias operator if needed
                $operator = $operatorsAliases[$operator] ?? $operator;

                if ($operator === 'in' || $operator === 'notin') {
                    $value = explode(',', $value);
                }
            }

            $filters[] = [$attr, $operator, $value];
        }

        return $filters;
    }

    public function applySearch(QueryFilter $queryFilter): QueryFilter
    {
        $searchQuery = $queryFilter->parameters['search'] ?? null;

        if (!is_string($searchQuery) || empty($searchQuery = trim($searchQuery))) {
            return $queryFilter;
        }

        /** @var BaseModel $model */
        $model = $queryFilter->builder->getModel();
        $searchableAttributes = $model->getSearchableAttributes();

        $queryFilter->builder->where(function ($query) use ($searchQuery, $searchableAttributes, $model) {
            foreach ($searchableAttributes as $attr) {
                if (str_contains($attr, '.')) {
                    $attrs = explode('.', $attr);
                    $subAttr = null;

                    if (count($attrs) === 3) {
                        [$table, $attr, $subAttr] = $attrs;
                    } else {
                        [$table, $attr] = $attrs;
                    }

                    $query->orWhereHas($table, function ($query) use ($attr, $searchQuery, $subAttr) {
                        if ($subAttr) {
                            $query->whereHas($attr, function ($query) use ($subAttr, $searchQuery) {
                                $query->where($subAttr, 'like', '%'. $searchQuery .'%');
                            });
                        } else {
                            $query->where($attr, 'like', '%'. $searchQuery .'%');
                        }
                    });
                } else {
                    $query->orWhere($model->getTable() .'.'. $attr, 'like', '%'. $searchQuery .'%');
                }
            }
        });

        return $queryFilter;
    }
}
