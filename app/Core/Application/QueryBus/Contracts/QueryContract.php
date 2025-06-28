<?php

declare(strict_types=1);

namespace App\Core\Application\QueryBus\Contracts;

interface QueryContract
{
    /**
     * Create a query from an array of data.
     *
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data): static;
}
