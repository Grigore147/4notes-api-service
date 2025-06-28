<?php

declare(strict_types=1);

use Tests\TestCase;
use Tests\FeatureTestCase;
use Illuminate\Support\Facades\Storage;
use App\Domains\Auth\AuthServiceProvider;
use App\Domains\Note\NoteServiceProvider;
use App\Core\Providers\CoreServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domains\Note\Domain\Entities\Note as NoteEntity;
use App\Domains\Note\Domain\Entities\Space as SpaceEntity;
use App\Domains\Note\Domain\Entities\Stack as StackEntity;
use App\Domains\Note\Domain\Entities\Notebook as NotebookEntity;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Note;

/*
|--------------------------------------------------------------------------
| Test Cases
|--------------------------------------------------------------------------
*/

uses(TestCase::class, RefreshDatabase::class)
    ->in('Unit')
    ->beforeEach(function () {
        Storage::fake('public');
    });

uses(FeatureTestCase::class, RefreshDatabase::class)
    ->in('Feature')
    ->beforeAll(function () {
        app()->register(CoreServiceProvider::class);
        app()->register(AuthServiceProvider::class);
        app()->register(NoteServiceProvider::class);

        // Fill database with some dummy data
        // By creating notes, it will automatically create notebooks, stacks, and spaces as well.
        Note::factory()->count(10)->create();
    })
    ->beforeEach(function () {
        Storage::fake('public');
    });


/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
*/

expect()->extend('toBeValidSpace', function (?array $data = null) {
    $spaceKeys = ['id', 'userId', 'name', 'description', 'createdAt', 'updatedAt'];

    if (is_array($this->value)) {
        return expect($this->value)
            ->toBeArray()
            ->toHaveKeys($spaceKeys);
    }

    expect($this->value)->toBeInstanceOf(SpaceEntity::class);
    expect($this->value->toArray())
        ->toBeArray()
        ->toHaveKeys($spaceKeys);
    
    if (is_array($data)) {
        expect($this->value)->toMatchArray($data);
    }
});

expect()->extend('toBeValidStack', function (?array $data = null) {
    $stackKeys = ['id', 'userId', 'spaceId', 'name', 'createdAt', 'updatedAt'];

    if (is_array($this->value)) {
        return expect($this->value)
            ->toBeArray()
            ->toHaveKeys($stackKeys);
    }

    expect($this->value)->toBeInstanceOf(StackEntity::class);
    expect($this->value->toArray())
        ->toBeArray()
        ->toHaveKeys($stackKeys);
    
    if (is_array($data)) {
        expect($this->value)->toMatchArray($data);
    }
});

expect()->extend('toBeValidNotebook', function (?array $data = null) {
    $notebookKeys = ['id', 'userId', 'spaceId', 'stackId', 'name', 'createdAt', 'updatedAt'];

    if (is_array($this->value)) {
        return expect($this->value)
            ->toBeArray()
            ->toHaveKeys($notebookKeys);
    }

    expect($this->value)->toBeInstanceOf(NotebookEntity::class);
    expect($this->value->toArray())
        ->toBeArray()
        ->toHaveKeys($notebookKeys);
    
    if (is_array($data)) {
        expect($this->value)->toMatchArray($data);
    }
});

expect()->extend('toBeValidNote', function (?array $data = null) {
    $noteKeys = ['id', 'userId', 'notebookId', 'title', 'content', 'createdAt', 'updatedAt'];

    if (is_array($this->value)) {
        return expect($this->value)
            ->toBeArray()
            ->toHaveKeys($noteKeys);
    }

    expect($this->value)->toBeInstanceOf(NoteEntity::class);
    expect($this->value->toArray())
        ->toBeArray()
        ->toHaveKeys($noteKeys);
    
    if (is_array($data)) {
        expect($this->value)->toMatchArray($data);
    }
});
