<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Database\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\Infrastructure\Database\Eloquent\Models\Concerns\HasFilters;

abstract class BaseModel extends Model // @pest-arch-ignore-line
{
    use HasFilters;

    /**
     * Safely get a relation or return a default value.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function relation(string $key, mixed $default = null):mixed {
        return $this->relationLoaded($key) ? $this->getRelation($key) : $default;
    }
}
