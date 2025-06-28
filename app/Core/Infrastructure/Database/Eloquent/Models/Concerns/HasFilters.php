<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Database\Eloquent\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasFilters
{
    /**
     * The default allowed filters for the model.
     *
     * @var array<string>
     */
    public $allowedFilters = ['selecting', 'filtering', 'including', 'ordering', 'pagination'];

    /**
     * Apply filters to the query.
     *
     * @param array<string, mixed> $parameters
     * @return self
     */
    public function scopeFilter($query, array $parameters = []): Builder
    {
        return $query->filter($parameters);
    }

    /**
     * Attribute aliases.
     *
     * @return array<string, string>
     */
    public function getAliasAttributes(): array
    {
        return $this->aliases ?? [];
    }

    /**
     * Get the attributes of the model on which the filters can be applied.
     *
     * @return array<string>
     */
    public function getFilterableAttributes(): array
    {
        if (!isset($this->filterable)) {
            return [];
        }

        return $this->filterable === ['*'] ? $this->getVisible() : $this->filterable;
    }

    /**
     * Get the searchable attributes for the model.
     *
     * @return array<string>
     */
    public function getSearchableAttributes(): array
    {
        if (!isset($this->searchable)) {
            return [];
        }

        return $this->searchable === ['*'] ? $this->getVisible() : $this->searchable;
    }

    /**
     * Get the sortable attributes for the model.
     *
     * @return array<string>
     */
    public function getSortableAttributes(): array
    {
        if (!isset($this->sortable)) {
            return [];
        }

        return $this->sortable === ['*'] ? $this->getVisible() : $this->sortable;
    }

    /**
     * Get relations that can be included.
     *
     * @return array<string>
     */
    public function getIncludableAttributes(): array
    {
        return $this->includable ?? [];
    }

    /**
     * Determine if the model can be filtered by the given attribute.
     *
     * @param string $attribute
     * @return bool
     */
    public function canBeFilteredBy(string $attribute): bool
    {
        return in_array($attribute, $this->getFilterableAttributes());
    }
}
