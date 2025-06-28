<?php

declare(strict_types=1);

arch('PHP preset')->preset()->php();
arch('Security preset')->preset()->security();

// arch()->preset()->laravel();

// arch('annotations')
//     ->expect('App')
//     ->toHavePropertiesDocumented()
//     ->toHaveMethodsDocumented();


arch('Strict types should be used')
    ->expect('App')
    ->toUseStrictTypes();

arch('Presentation should not be used in Core, Application, Domain or Infrastructure Layer')
    ->expect('App\Domains\Note\Presentation')
    ->toOnlyBeUsedIn('App\Domains\Note\Presentation')
    ->ignoring('App\Core\Presentation\Http\Controllers');

arch('Application should only be accessed by Presentation, Domain or Infrastructure Layer')
    ->expect('App\Domains\Note\Application')
    ->toOnlyBeUsedIn([
        'App\Core',
        'App\Domains\Note\Presentation',
        'App\Domains\Note\Domain',
        'App\Domains\Note\Infrastructure'
    ])
    ->ignoring('App\Domains\Note\Application');

arch('Infrastructure should only be accessed by Application Layer')
    ->expect('App\Domains\Note\Infrastructure')
    ->toOnlyBeUsedIn([
        'App\Core',
        'App\Domains\Note\Application'
    ])
    ->ignoring([
        'App\Domains\Note\NoteServiceProvider',
        'App\Domains\Note\Infrastructure'
    ]);

arch('Domain should only be accessed by Application Layer')
    ->expect('App\Domains\Note\Domain')
    ->toOnlyBeUsedIn([
        'App\Core',
        'App\Domains\Note\Application'
    ])
    ->ignoring([
        'App\Domains\Note\Domain',
        'App\Domains\Note\NoteServiceProvider',
        // Infrastructure repositories should be able to access Domain Repository Interfaces and Entities
        'App\Domains\Note\Infrastructure\Repositories'
    ]);

