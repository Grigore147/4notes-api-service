<?php

declare(strict_types=1);

namespace App\Core\Domain\Entities;

/**
 * HasAttributes Contract
 *
 * Light version of Laravel's Eloquent HasAttributes trait.
 */
interface HasAttributesContract
{
    /**
     * HasAttributes constructor.
     *
     * @param array  $attributes
     * @return void
     */
    public function initAttributes(array $attributes = []): void;

    /**
     * Determine whether an attribute exists on the entity.
     *
     * @param string  $key
     * @return bool
     */
    public function hasAttribute(string $key): bool;

    /**
     * Get an attribute from the entity.
     *
     * @param string  $key
     * @param mixed  $default
     * @return mixed
     */
    public function getAttribute(string $key, mixed $default = null): mixed;

    /**
     * Determine whether an original attribute exists on the entity.
     *
     * @param string  $key
     * @return bool
     */
    public function hasOriginal(string $key): bool;

    /**
     * Get an original attribute from the entity.
     *
     * @param string  $key
     * @param mixed  $default
     * @return mixed
     */
    public function getOriginal(string $key, mixed $default = null): mixed;

    /**
     * Determine whether an changed attribute exists on the entity.
     *
     * @param string  $key
     * @return bool
     */
    public function hasChanged(string $key): bool;

    /**
     * Get an changed attribute from the entity.
     *
     * @param string  $key
     * @param mixed  $default
     * @return mixed
     */
    public function getChanged(string $key, mixed $default = null): mixed;

    /**
     * Get entity's attributes.
     *
     * @return array
     */
    public function getAttributes(): array;

    /**
     * Get entity's original attributes.
     *
     * @return array
     */
    public function getOriginals(): array;

    /**
     * Get entity's changed attributes.
     *
     * @return array
     */
    public function getChanges(): array;

    /**
     * Determine if the entity or any of the given attribute(s) have been modified.
     *
     * @param  array|string|null  $attributes
     * @return bool
     */
    public function isDirty($attributes = null): bool;

    /**
     * Determine if the entity or all the given attribute(s) have remained the same.
     *
     * @param  array|string|null  $attributes
     * @return bool
     */
    public function isClean($attributes = null): bool;

    /**
     * Discard attribute changes and reset the attributes to their original state.
     *
     * @return $this
     */
    public function discardChanges(): static;

    /**
     * Determine if the entity or any of the given attribute(s) were changed when the entity was last saved.
     *
     * @param  array|string|null  $attributes
     * @return bool
     */
    public function wasChanged($attributes = null): bool;

    /**
     * Determine if any of the given attributes were changed when the entity was last saved.
     *
     * @param  array  $changes
     * @param  array|string|null  $attributes
     * @return bool
     */
    public function hasChanges($changes, $attributes = null): bool;

    /**
     * Get the attributes that have been changed since the last sync.
     *
     * @return array
     */
    public function getDirtyAttributes(): array;

    /**
     * Determine if the new and old values for a given key are equivalent.
     *
     * @param  string  $key
     * @return bool
     */
    public function originalIsEquivalent($key): bool;

    /**
     * Get a subset of the entity's attributes.
     *
     * @param  array|mixed  $attributes
     * @return array
     */
    public function only($attributes): array;

    /**
     * Sync the original attributes with the current.
     *
     * @return $this
     */
    public function syncOriginal(): HasAttributes;

    /**
     * Sync a single original attribute with its current value.
     *
     * @param  string  $attribute
     * @return HasAttributes
     */
    public function syncOriginalAttribute($attribute): HasAttributes;

    /**
     * Sync multiple original attribute with their current values.
     *
     * @param  array|string  $attributes
     * @return HasAttributes
     */
    public function syncOriginalAttributes($attributes): HasAttributes;

    /**
     * Sync the changed attributes.
     *
     * @return $this
     */
    public function syncChanges(): static;

    /**
     * Set a given attribute on the entity.
     *
     * @param string  $key
     * @param mixed  $value
     * @return static
     */
    public function setAttribute(string $key, mixed $value): static;

    /**
     * Set a given attributes on the entity.
     *
     * @param array  $attributes
     * @return static
     */
    public function setAttributes(array $attributes): static;

    /**
     * Determine if a set mutator exists for an attribute.
     *
     * @param  string  $key
     * @return bool
     */
    public function hasSetMutator($key): bool;

    /**
     * Set the value of an attribute using its mutator.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    public function setMutatedAttributeValue($key, $value): mixed;
}
