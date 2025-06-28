<?php

declare(strict_types=1);

namespace App\Domains\Note\Infrastructure\Database\Eloquent\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Infrastructure\Database\Eloquent\Models\BaseModel;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Note;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Space;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Stack;
use App\Domains\Note\Infrastructure\Database\Builders\NotebookBuilder;
use App\Domains\Note\Infrastructure\Database\Eloquent\Factories\NotebookFactory;

final class Notebook extends BaseModel
{
    use HasUuids, HasFactory;

    public static $factory = NotebookFactory::class;

    public static string $builder = NotebookBuilder::class;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id', 'user_id', 'space_id', 'stack_id', 'name', 'created_at', 'updated_at'];
    protected $visible = ['id', 'user_id', 'space_id', 'stack_id', 'name', 'created_at', 'updated_at'];

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'space_id' => 'string',
        'stack_id' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $aliases = [
        'userId' => 'user_id',
        'spaceId' => 'space_id',
        'stackId' => 'stack_id',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at'
    ];
    protected $filterable = ['*'];
    protected $searchable = [
        'name'
    ];
    protected $sortable = ['*'];
    protected $includable = [
        'space',
        'stack',
    ];

    public function space()
    {
        return $this->belongsTo(Space::class, 'space_id', 'id');
    }

    public function stack()
    {
        return $this->belongsTo(Stack::class, 'stack_id', 'id');
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'notebook_id', 'id');
    }
}
