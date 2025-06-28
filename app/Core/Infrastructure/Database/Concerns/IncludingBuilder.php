<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Database\Concerns;

use Closure;
use Illuminate\Support\Str;
use App\Core\Infrastructure\Database\Concerns\QueryFilter;
use App\Core\Infrastructure\Database\Eloquent\Models\BaseModel;

final class IncludingBuilder
{
    /**
     * Handle the query.
     *
     * @param QueryFilter $queryFilter
     */
    public function handle(QueryFilter $queryFilter, Closure $next)
    {
        /** @var BaseModel $model */
        $model = $queryFilter->builder->getModel();

        $includes = $this->parseIncludes($queryFilter->parameters);
        $parameters = array_intersect($includes, $model->getIncludableAttributes());

        foreach ($parameters as $relation) {
            $method = 'include'.Str::studly($relation);

            if (method_exists($queryFilter->builder, $method)) {
                $queryFilter->builder->{$method}($queryFilter);
            } elseif (method_exists($model, $method)) {
                $model->{$method}($queryFilter->builder, $queryFilter);
            } else {
                $parts = explode('.', $relation);

                $relation = collect($parts)->map(
                    fn($value) => Str::camel($value)
                )->implode('.');
                
                $queryFilter->builder->with($relation);
            }
        }

        return $next($queryFilter);
    }

    /**
     * Parse the includes.
     *
     * @param array $parameters
     * @return array
     */
    public function parseIncludes(array $parameters): array
    {
        $includes = $parameters['include'] ?? [];

        if (is_array($includes)) { return $includes; }

        return array_unique(
            array_filter(
                array_map('trim',
                    explode(',', $includes)
                )
            )
        );
    }
}
