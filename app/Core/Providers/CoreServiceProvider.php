<?php

declare(strict_types=1);

namespace App\Core\Providers;

use Illuminate\Support\ServiceProvider;

final class CoreServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap.
     */
    public function boot(): void
    {
        require_once __DIR__ . '/../Support/Helpers/Str.php';
        require_once __DIR__ . '/../Support/Helpers/File.php';
        require_once __DIR__ . '/../Support/Macros/RequestMacros.php';
    }
}
