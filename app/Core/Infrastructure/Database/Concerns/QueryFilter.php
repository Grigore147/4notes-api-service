<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Database\Concerns;

use Illuminate\Database\Eloquent\Builder;

final class QueryFilter
{
    public function __construct(
        public Builder $builder,
        public array $parameters,
        public array $requestedFieldsets = []
    ) {}

    /**
     * Replace the field aliases with the actual field names.
     *
     * @param array $filters
     * @return Builder
     */
    public function replaceAliases(array $fields, array $aliases): array
    {
        return array_map(function ($field) use ($aliases) {
            return $aliases[$field] ?? $field;
        }, $fields);
    }

    /**
     * Parse the fieldsets.
     *
     * @param array $fieldsets
     * @return array<string, array>
     */
    public function parseFieldsets(array $fieldsets): array
    {
        $sets = [];

        foreach ($fieldsets as $type => $fields) {
            if (is_string($fields)) {
                $fields = explode(',', $fields);
            }

            $sets[$type] = array_unique(array_filter($fields));
        }
        
        return $sets;
    }

    /**
     * Filter the fieldsets to only include the allowed attributes.
     *
     * @param array $fieldsets
     * @param array $attributes
     * @return array<string, array>
     */
    public function filterFieldsets(array $fieldsets, array $attributes): array
    {
        $filterFieldset = [];

        foreach ($fieldsets as $set => $fields) {
            if (isset($attributes[$set])) {
                $filterFieldset[$set] = array_intersect($fields, $attributes[$set]);
            }
        }

        return $filterFieldset;
    }
}
