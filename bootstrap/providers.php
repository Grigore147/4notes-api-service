<?php

return [
    App\Core\Providers\CoreServiceProvider::class,
    App\Domains\Auth\AuthServiceProvider::class,
    App\Domains\Note\NoteServiceProvider::class,
    App\Providers\HorizonServiceProvider::class,
    App\Providers\TelescopeServiceProvider::class,
    Barryvdh\Debugbar\ServiceProvider::class,
];
