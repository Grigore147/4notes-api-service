<?php

declare(strict_types=1);

namespace App\Domains\Auth;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as BaseAuthServiceProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Auth;

use App\Domains\Auth\AuthServiceUserProvider;
use App\Domains\Auth\Domain\Services\AuthService;

final class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application authentication / authorization services.
     */
    public function boot(): void
    {
        // $this->app->bind(UserRepositoryContract::class, EloquentUserRepository::class);

        Auth::provider('auth-service', function (Application $app, array $config): UserProvider {
            return new AuthServiceUserProvider($config, new AuthService($config));
        });
    }
}
