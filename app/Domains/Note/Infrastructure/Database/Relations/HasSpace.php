<?php

declare(strict_types=1);

namespace App\Domains\Note\Infrastructure\Database\Relations;

use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Space;

trait HasSpace
{
    public function space()
    {
        return $this->belongsTo(Space::class, 'space_id', 'id');
    }
}
