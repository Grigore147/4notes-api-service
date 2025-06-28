<?php

declare(strict_types=1);

namespace App\Domains\Note\Infrastructure\Database\Eloquent\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Infrastructure\Database\Eloquent\Models\BaseModel;
use App\Domains\Note\Infrastructure\Database\Eloquent\Factories\NoteFactory;
use App\Domains\Note\Infrastructure\Database\Builders\NoteBuilder;

final class Note extends BaseModel
{
    use HasUuids, HasFactory;

    public static $factory = NoteFactory::class;

    public static string $builder = NoteBuilder::class;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id', 'user_id', 'notebook_id', 'title', 'content', 'created_at', 'updated_at'];
    protected $visible = ['id', 'user_id', 'notebook_id', 'title', 'content', 'created_at', 'updated_at'];

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'notebook_id' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $aliases = [
        'userId' => 'user_id',
        'notebookId' => 'notebook_id',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at'
    ];
    protected $filterable = ['*'];
    protected $searchable = [
        'title',
        'content'
    ];
    protected $sortable = ['*'];
    protected $includable = [
        'space',
        'stack',
        'notebook'
    ];

    public function space()
    {
        return $this->hasOneThrough(
            Space::class, Notebook::class, 'id', 'id', 'notebook_id', 'space_id'
        );
    }

    public function stack()
    {
        return $this->hasOneThrough(
            Stack::class, Notebook::class, 'id', 'id', 'notebook_id', 'stack_id'
        );
    }

    public function notebook()
    {
        return $this->belongsTo(Notebook::class, 'notebook_id', 'id');
    }
}
