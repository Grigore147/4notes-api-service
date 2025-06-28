<?php

declare(strict_types=1);

namespace App\Core\Application\Validation;

abstract class Rules // @pest-arch-ignore-line
{
    /**
     * Resource List rules.
     *
     * @return array
     */
    public static function list(): array
    {
        return [];
    }

    /**
     * Resource Get rules.
     *
     * @return array
     */
    public static function get(): array
    {
        return [];
    }

    /**
     * Resource Create rules.
     *
     * @return array
     */
    public static function create(): array
    {
        return [];
    }

    /**
     * Resource Update rules.
     *
     * @return array
     */
    public static function update(): array
    {
        return [];
    }

    /**
     * Resource Delete rules.
     *
     * @return array
     */
    public static function delete(): array
    {
        return [];
    }
}
