<?php

declare(strict_types=1);

namespace App\Domains\Note\Infrastructure\Database\Eloquent\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Infrastructure\Database\Eloquent\Models\BaseModel;
use App\Domains\Note\Infrastructure\Database\Eloquent\Factories\SpaceFactory;
use App\Domains\Note\Infrastructure\Database\Builders\SpaceBuilder;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Stack;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Notebook;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Note;

final class Space extends BaseModel
{
    use HasUuids, HasFactory;

    public static string $factory = SpaceFactory::class;
    public static string $builder = SpaceBuilder::class;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id', 'user_id', 'name', 'description', 'created_at', 'updated_at'];
    protected $visible = ['id', 'user_id', 'name', 'description', 'created_at', 'updated_at'];

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $aliases = [
        'userId' => 'user_id',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at'
    ];
    protected $filterable = ['*'];
    protected $searchable = [
        'name',
        'description'
    ];
    protected $sortable = ['*'];
    protected $includable = [];

    public function stacks()
    {
        return $this->hasMany(Stack::class, 'space_id', 'id');
    }

    public function notebooks()
    {
        return $this->hasMany(Notebook::class, 'space_id', 'id');
    }

    public function notes()
    {
        return $this->hasManyThrough(
            Note::class, Notebook::class, 'space_id', 'notebook_id', 'id', 'id'
        );
    }
}
