<?php

declare(strict_types=1);

namespace App\Domains\Auth\Contracts;

use Illuminate\Contracts\Auth\Authenticatable as IlluminateAuthenticatable;

interface Authenticatable extends IlluminateAuthenticatable
{
    /**
     * Check if the user is an admin
     *
     * @return bool
     */
    public function isAdmin(): bool;
}
