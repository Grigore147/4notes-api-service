<?php

use Illuminate\Support\Facades\Route;
use App\Domains\Note\Presentation\Api\Controllers\NotesController;
use App\Domains\Note\Presentation\Api\Controllers\SpacesController;
use App\Domains\Note\Presentation\Api\Controllers\StacksController;
use App\Domains\Note\Presentation\Api\Controllers\NotebooksController;

Route::middleware('auth:api')
->group(function () {
    Route::prefix('spaces')
        ->controller(SpacesController::class)
        ->group(function () {
            Route::get('/', 'list')->name('list-spaces');
            Route::get('/{space}', 'get')->name('get-space');
            Route::post('/', 'create')->name('create-space');
            Route::put('/{space}', 'update')->name('update-space');
            Route::delete('/{space}', 'delete')->name('delete-space');
        });

    Route::prefix('stacks')
        ->controller(StacksController::class)
        ->group(function () {
            Route::get('/', 'list')->name('list-stacks');
            Route::get('/{stack}', 'get')->name('get-stack');
            Route::post('/', 'create')->name('create-stack');
            Route::put('/{stack}', 'update')->name('update-stack');
            Route::delete('/{stack}', 'delete')->name('delete-stack');
        });

    Route::prefix('notebooks')
        ->controller(NotebooksController::class)
        ->group(function () {
            Route::get('/', 'list')->name('list-notebooks');
            Route::get('/{notebook}', 'get')->name('get-notebook');
            Route::post('/', 'create')->name('create-notebook');
            Route::put('/{notebook}', 'update')->name('update-notebook');
            Route::delete('/{notebook}', 'delete')->name('delete-notebook');
        });

    Route::prefix('notes')
        ->controller(NotesController::class)
        ->group(function () {
            Route::get('/', 'list')->name('list-notes');
            Route::get('/{note}', 'get')->name('get-note');
            Route::post('/', 'create')->name('create-note');
            Route::put('/{note}', 'update')->name('update-note');
            Route::delete('/{note}', 'delete')->name('delete-note');
        });
});
