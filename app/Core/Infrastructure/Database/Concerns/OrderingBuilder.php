<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Database\Concerns;

use Closure;
use Illuminate\Support\Str;
use App\Core\Infrastructure\Database\Concerns\QueryFilter;
use App\Core\Infrastructure\Database\Eloquent\Models\BaseModel;

final class OrderingBuilder
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
        
        $sorting = $this->filterSortableAttributes(
            $model,
            $this->parseSorting($queryFilter->parameters)
        );

        foreach ($sorting as $attr => $order) {
            $method = 'sort'.Str::studly(str_replace('.', '_', $attr));

            if (method_exists($queryFilter->builder, $method)) {
                $queryFilter->builder->{$method}($order, $queryFilter);
            } elseif (method_exists($model, $method)) {
                $model->{$method}($queryFilter->builder, $order, $queryFilter);
            } else {
                $queryFilter->builder->orderBy($model->getTable().'.'.$attr, $order);
            }
        }

        return $next($queryFilter);
    }

    /**
     * Parse the sorting parameter.
     *
     * @param array $parameters
     * @return array
     */
    public function parseSorting(array $parameters): array
    {
        if (empty($parameters['sort'])) {
            return [];
        }

        // If the sorting parameter is an array, we assume that the structure is as follows: ['attr' => 'order']
        if (is_array($parameters['sort'])) {
            // Ensure that the order is either 'asc' or 'desc'
            array_walk($parameters['sort'], function (&$value) {
                $value = $value === 'desc' ? 'desc' : 'asc';
            });

            return $parameters['sort'];
        }

        // If the sorting parameter is a string, we assume that the structure is as follows: 'attr1,attr2,-attr3'
        $parameters = explode(',', $parameters['sort']);

        return array_reduce($parameters, function ($res, $attr) {
            $order = 'asc';

            if ($attr[0] === '-') {
                $attr = substr($attr, 1);
                $order = 'desc';
            }

            $res[$attr] = $order;

            return $res;
        }, []);
    }

    /**
     * Get only the sortable attributes that are allowed to be sorted for the model.
     *
     * @param BaseModel $model
     * @param array $sorting Sorting parameters
     * @return array Allowed sortable attributes
     */
    public function filterSortableAttributes(BaseModel $model, array $sorting): array
    {
        $attrAliases = $model->getAliasAttributes();
        $sortableAttributes = $model->getSortableAttributes();
        $sortable = [];

        foreach ($sorting as $attr => $order) {
            $attr = $attrAliases[$attr] ?? $attr;

            if (in_array($attr, $sortableAttributes)) {
                $sortable[$attr] = $order;
            }
        }

        return $sortable;
    }
}
