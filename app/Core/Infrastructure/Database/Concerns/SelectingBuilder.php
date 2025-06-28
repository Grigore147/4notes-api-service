<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Database\Concerns;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\Core\Infrastructure\Database\Concerns\QueryFilter;

final class SelectingBuilder
{
    /**
     * Handle the query.
     *
     * @param QueryFilter $queryFilter
     */
    public function handle(QueryFilter $queryFilter, Closure $next)
    {
        /** @var Model $model */
        $model = $queryFilter->builder->getModel();
        $table = $model->getTable();

        $fieldsets = $queryFilter->parameters[$this->fieldsKeyName()] ?? [];

        if (empty($fieldsets)) {
            return $next($queryFilter);
        }

        // In the case when the resource is not specified or we got directly an array of fields,
        // we assume that the fields are for the current model.
        // Example: fields=id,name,age, instead of fields[users]=id,name,age
        if (is_string($fieldsets) || array_is_list($fieldsets)) {
            $fieldsets = [$table => $fieldsets];
        }
        
        $fieldsets = $queryFilter->parseFieldsets($fieldsets);

        // Get the requested fields for the current model
        $requestedFields = $fieldsets[$table] ?? [];

        foreach ($requestedFields as $field) {
            // NOTE:
            // In the custom selector method, we can use the QueryBuilder instance
            // to add a more complex select statement to the query if needed for relations
            // or other cases like aggregation functions...
            $method = 'select'.Str::studly(str_replace('.', '_', $field));

            if (method_exists($queryFilter->builder, $method)) {
                $queryFilter->builder->$method($queryFilter);
            }
            if (method_exists($model, $method)) {
                $model->$method($queryFilter->builder, $queryFilter);
            }
        }

        // Save requested fields to the query filter for later use if needed
        $queryFilter->requestedFieldsets = $fieldsets;

        return $next($queryFilter);
    }

    /**
     * Get the key name for the fields in the request parameters.
     *
     * @return string
     */
    public function fieldsKeyName(): string
    {
        return 'fields';
    }
}
