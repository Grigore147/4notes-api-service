<?php

declare(strict_types=1);

namespace App\Domains\Note\Infrastructure\Database\Eloquent\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Infrastructure\Database\Eloquent\Models\BaseModel;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Space;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Notebook;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Note;
use App\Domains\Note\Infrastructure\Database\Eloquent\Factories\StackFactory;
use App\Domains\Note\Infrastructure\Database\Builders\StackBuilder;

final class Stack extends BaseModel
{
    use HasUuids, HasFactory;

    public static $factory = StackFactory::class;

    public static string $builder = StackBuilder::class;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id', 'user_id', 'space_id', 'name', 'created_at', 'updated_at'];
    protected $visible = ['id', 'user_id', 'space_id', 'name', 'created_at', 'updated_at'];

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'space_id' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $aliases = [
        'userId' => 'user_id',
        'spaceId' => 'space_id',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at'
    ];
    protected $filterable = ['*'];
    protected $searchable = [
        'name'
    ];
    protected $sortable = ['*'];
    protected $includable = [
        'space'
    ];

    public function space()
    {
        return $this->belongsTo(Space::class, 'space_id', 'id');
    }

    public function notebooks()
    {
        return $this->hasMany(Notebook::class, 'stack_id', 'id');
    }

    public function notes()
    {
        return $this->hasManyThrough(
            Note::class, Notebook::class, 'stack_id', 'notebook_id', 'id', 'id'
        );
    }

    // protected static function newFactory()
    // {
    //     return StackFactory::new();
    // }
}
