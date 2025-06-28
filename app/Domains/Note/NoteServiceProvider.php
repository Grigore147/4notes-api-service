<?php

declare(strict_types=1);

namespace App\Domains\Note;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Core\Domain\DomainServiceProvider;
use App\Domains\Auth\Infrastructure\Models\User;
use App\Domains\Note\Domain\Repositories\NotesRepository;
use App\Domains\Note\Domain\Repositories\SpacesRepository;
use App\Domains\Note\Domain\Repositories\StacksRepository;
use App\Domains\Note\Domain\Repositories\NotebooksRepository;

final class NoteServiceProvider extends DomainServiceProvider
{
    /**
     * @inheritDoc
     */
    public string $name = 'note';

    /**
     * @inheritDoc
     */
    public string $domain = '4notes.app';

    /**
     * @inheritDoc
     */
    public array $bindings = [
        \App\Domains\Note\Domain\Services\Contracts\SpaceService::class => \App\Domains\Note\Domain\Services\SpaceService::class,
        \App\Domains\Note\Domain\Repositories\SpacesRepository::class => \App\Domains\Note\Infrastructure\Repositories\SpacesRepository::class,
        \App\Domains\Note\Domain\Entities\Contracts\SpaceEntityContract::class => \App\Domains\Note\Domain\Entities\Space::class,

        \App\Domains\Note\Domain\Services\Contracts\StackService::class => \App\Domains\Note\Domain\Services\StackService::class,
        \App\Domains\Note\Domain\Repositories\StacksRepository::class => \App\Domains\Note\Infrastructure\Repositories\StacksRepository::class,
        \App\Domains\Note\Domain\Entities\Contracts\StackEntityContract::class => \App\Domains\Note\Domain\Entities\Stack::class,

        \App\Domains\Note\Domain\Services\Contracts\NotebookService::class => \App\Domains\Note\Domain\Services\NotebookService::class,
        \App\Domains\Note\Domain\Repositories\NotebooksRepository::class => \App\Domains\Note\Infrastructure\Repositories\NotebooksRepository::class,
        \App\Domains\Note\Domain\Entities\Contracts\NotebookEntityContract::class => \App\Domains\Note\Domain\Entities\Notebook::class,

        \App\Domains\Note\Domain\Services\Contracts\NoteService::class => \App\Domains\Note\Domain\Services\NoteService::class,
        \App\Domains\Note\Domain\Repositories\NotesRepository::class => \App\Domains\Note\Infrastructure\Repositories\NotesRepository::class,
        \App\Domains\Note\Domain\Entities\Contracts\NoteEntityContract::class => \App\Domains\Note\Domain\Entities\Note::class,
    ];

    /**
     * @inheritDoc
     */
    public function registerRouteBindings(): void
    {
        $repositories = [
            'space'    => SpacesRepository::class,
            'stack'    => StacksRepository::class,
            'notebook' => NotebooksRepository::class,
            'note'     => NotesRepository::class
        ];
        $uuidKeys = ['space', 'stack', 'notebook', 'note'];

        Route::whereUuid($uuidKeys);

        foreach ($repositories as $key => $repository) {
            Route::bind($key, function ($value) use ($repository) {
                /** @var User $user */
                $user = Auth::user();

                return app($repository)->getById(
                    $value,
                    // NOTE: This method of applying the User ID filter here is ok for simple use cases,
                    //       but Gates / Policies or Request validation should be used for more complex cases,
                    //       especially when dealing with complex RBAC or ABAC rules.
                    //       Additionally, we may want to load only few fields, not the whole entity/model data from the database.
                    $user?->isAdmin() ? [] : ['userId' => Auth::id()]
                );
            });
        }
    }
}
