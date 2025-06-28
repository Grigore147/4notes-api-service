<?php

declare(strict_types=1);

namespace App\Domains\Note\Infrastructure\Database\Relations;

use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Notebook;

trait HasNotebook
{
    public function notebook()
    {
        return $this->belongsTo(Notebook::class, 'notebook_id', 'id');
    }
}
