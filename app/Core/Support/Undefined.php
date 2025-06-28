<?php

declare(strict_types=1);

namespace App\Core\Support;

/**
 * PotentiallyUndefined
 *
 * An interface to represent objects that can be considered "undefined".
 */
interface PotentiallyUndefined
{
    /**
     * Determine if the object should be considered "undefined".
     *
     * @return bool
     */
    public function isUndefined();
}

/**
 * Undefined
 *
 * A class to represent an undefined value.
 */
class Undefined implements PotentiallyUndefined
{
    protected $hasDefault = false;

    public function __construct(
        /**
         * The default value.
         *
         * @var mixed
         */
        protected mixed $default = null
    ) {
        $this->hasDefault = func_num_args() > 0;
    }

    /**
     * Determine if the object has a default value.
     *
     * @return bool
     */
    public function hasDefault(): bool
    {
        return $this->hasDefault;
    }

    /**
     * Get the default value.
     *
     * @return mixed
     */
    public function getDefault(): mixed
    {
        return $this->default;
    }

    /**
     * Determine if the object should be considered "undefined".
     *
     * @return bool
     */
    public function isUndefined()
    {
        return true;
    }
}
