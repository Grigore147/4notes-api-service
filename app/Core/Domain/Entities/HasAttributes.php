<?php

declare(strict_types=1);

namespace App\Core\Domain\Entities;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * HasAttributes
 *
 * Light version of Laravel's Eloquent HasAttributes trait.
 */
trait HasAttributes
{
    /**
     * The entity's attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * The entity attribute's original state.
     *
     * @var array
     */
    protected $originals = [];

    /**
     * The changed entity attributes.
     *
     * @var array
     */
    protected $changes = [];

    /**
     * HasAttributes constructor.
     *
     * @param array  $attributes
     * @return void
     */
    public function initAttributes(array $attributes = []): void
    {
        $this->attributes = $attributes;
        $this->originals = $attributes;
        $this->changes = [];
    }

    /**
     * Determine whether an attribute exists on the entity.
     *
     * @param string  $key
     * @return bool
     */
    public function hasAttribute(string $key): bool
    {
        if (!$key) { return false; }

        return array_key_exists($key, $this->attributes);
    }

    /**
     * Get an attribute from the entity.
     *
     * @param string  $key
     * @param mixed  $default
     * @return mixed
     */
    public function getAttribute(string $key, mixed $default = null): mixed
    {
        if (!$key) { return false; }
        
        return $this->hasAttribute($key) ? $this->attributes[$key] : $default;
    }

    /**
     * Determine whether an original attribute exists on the entity.
     *
     * @param string  $key
     * @return bool
     */
    public function hasOriginal(string $key): bool
    {
        if (!$key) { return false; }

        return array_key_exists($key, $this->originals);
    }

    /**
     * Get an original attribute from the entity.
     *
     * @param string  $key
     * @param mixed  $default
     * @return mixed
     */
    public function getOriginal(string $key, mixed $default = null): mixed
    {
        if (!$key) { return false; }
        
        return $this->hasOriginal($key) ? $this->originals[$key] : $default;
    }

    /**
     * Determine whether an changed attribute exists on the entity.
     *
     * @param string  $key
     * @return bool
     */
    public function hasChanged(string $key): bool
    {
        if (!$key) { return false; }

        return array_key_exists($key, $this->changes);
    }

    /**
     * Get an changed attribute from the entity.
     *
     * @param string  $key
     * @param mixed  $default
     * @return mixed
     */
    public function getChanged(string $key, mixed $default = null): mixed
    {
        if (!$key) { return false; }
        
        return $this->hasChanged($key) ? $this->changes[$key] : $default;
    }

    /**
     * Get entity's attributes.
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Get entity's original attributes.
     *
     * @return array
     */
    public function getOriginals(): array
    {
        return $this->originals;
    }

    /**
     * Get entity's changed attributes.
     *
     * @return array
     */
    public function getChanges(): array
    {
        return $this->changes;
    }

    /**
     * Determine if the entity or any of the given attribute(s) have been modified.
     *
     * @param  array|string|null  $attributes
     * @return bool
     */
    public function isDirty($attributes = null): bool
    {
        return $this->hasChanges(
            $this->getDirtyAttributes(), is_array($attributes) ? $attributes : func_get_args()
        );
    }

    /**
     * Determine if the entity or all the given attribute(s) have remained the same.
     *
     * @param  array|string|null  $attributes
     * @return bool
     */
    public function isClean($attributes = null): bool
    {
        return ! $this->isDirty(...func_get_args());
    }

    /**
     * Discard attribute changes and reset the attributes to their original state.
     *
     * @return $this
     */
    public function discardChanges(): static
    {
        [$this->attributes, $this->changes] = [$this->originals, []];

        return $this;
    }

    /**
     * Determine if the entity or any of the given attribute(s) were changed when the entity was last saved.
     *
     * @param  array|string|null  $attributes
     * @return bool
     */
    public function wasChanged($attributes = null): bool
    {
        return $this->hasChanges(
            $this->getChanges(), is_array($attributes) ? $attributes : func_get_args()
        );
    }

    /**
     * Determine if any of the given attributes were changed when the entity was last saved.
     *
     * @param  array  $changes
     * @param  array|string|null  $attributes
     * @return bool
     */
    public function hasChanges($changes, $attributes = null): bool
    {
        if (empty($attributes)) {
            return count($changes) > 0;
        }

        foreach (Arr::wrap($attributes) as $attribute) {
            if (array_key_exists($attribute, $changes)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the attributes that have been changed since the last sync.
     *
     * @return array
     */
    public function getDirtyAttributes(): array
    {
        $dirty = [];

        foreach ($this->getAttributes() as $key => $value) {
            if (! $this->originalIsEquivalent($key)) {
                $dirty[$key] = $value;
            }
        }

        return $dirty;
    }

    /**
     * Determine if the new and old values for a given key are equivalent.
     *
     * @param  string  $key
     * @return bool
     */
    public function originalIsEquivalent($key): bool
    {
        if (!array_key_exists($key, $this->originals)) {
            return false;
        }

        $attribute = Arr::get($this->attributes, $key);
        $original = Arr::get($this->originals, $key);

        if ($attribute === $original) {
            return true;
        } elseif ($attribute instanceof Carbon && $original instanceof Carbon) {
            return $attribute->eq($original);
        }

        return is_numeric($attribute) && is_numeric($original)
            && strcmp((string) $attribute, (string) $original) === 0;
    }

    /**
     * Get a subset of the entity's attributes.
     *
     * @param  array|mixed  $attributes
     * @return array
     */
    public function only($attributes): array
    {
        $results = [];

        foreach (is_array($attributes) ? $attributes : func_get_args() as $attribute) {
            $results[$attribute] = $this->getAttribute($attribute);
        }

        return $results;
    }

    /**
     * Sync the original attributes with the current.
     *
     * @return $this
     */
    public function syncOriginal(): HasAttributes
    {
        $this->originals = $this->getAttributes();

        return $this;
    }

    /**
     * Sync a single original attribute with its current value.
     *
     * @param  string  $attribute
     * @return HasAttributes
     */
    public function syncOriginalAttribute($attribute): HasAttributes
    {
        return $this->syncOriginalAttributes($attribute);
    }

    /**
     * Sync multiple original attribute with their current values.
     *
     * @param  array|string  $attributes
     * @return HasAttributes
     */
    public function syncOriginalAttributes($attributes): HasAttributes
    {
        $attributes = is_array($attributes) ? $attributes : func_get_args();

        $entityAttributes = $this->getAttributes();

        foreach ($attributes as $attribute) {
            $this->originals[$attribute] = $entityAttributes[$attribute];
        }

        return $this;
    }

    /**
     * Sync the changed attributes.
     *
     * @return $this
     */
    public function syncChanges(): static
    {
        $this->changes = $this->getDirtyAttributes();

        return $this;
    }

    /**
     * Set a given attribute on the entity.
     *
     * @param string  $key
     * @param mixed  $value
     * @return static
     */
    public function setAttribute(string $key, mixed $value): static
    {
        $this->attributes[$key] = $this->hasSetMutator($key)
            ? $this->setMutatedAttributeValue($key, $value)
            : $value;

        return $this;
    }

    /**
     * Set a given attributes on the entity.
     *
     * @param array  $attributes
     * @return static
     */
    public function setAttributes(array $attributes): static
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }

        return $this;
    }

    /**
     * Determine if a set mutator exists for an attribute.
     *
     * @param  string  $key
     * @return bool
     */
    public function hasSetMutator($key): bool
    {
        return method_exists($this, 'set'.Str::studly($key).'Attribute');
    }

    /**
     * Set the value of an attribute using its mutator.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    public function setMutatedAttributeValue($key, $value): mixed
    {
        return $this->{'set'.Str::studly($key).'Attribute'}($value);
    }
}
