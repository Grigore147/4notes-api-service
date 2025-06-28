<?php

declare(strict_types=1);

namespace App\Domains\Note\Infrastructure\Database\Relations;

use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Stack;

trait HasStack
{
    public function stack()
    {
        return $this->belongsTo(Stack::class, 'stack_id', 'id');
    }
}
