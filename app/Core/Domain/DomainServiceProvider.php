<?php

declare(strict_types=1);

namespace App\Core\Domain;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Illuminate\Database\Eloquent\Model;
use App\Core\Support\Enums\ResponseStatus;
use App\Core\Application\QueryBus\QueryBus;
use App\Core\Application\CommandBus\CommandBus;
use App\Core\Application\QueryBus\Contracts\QueryBus as QueryBusContract;
use App\Core\Application\CommandBus\Contracts\CommandBus as CommandBusContract;
use App\Core\Application\EventBus\Contracts\DomainEvent as DomainEventContract;
use App\Core\Application\Services\DomainEventsPublisher\DomainEventPublisher;
use App\Core\Application\Services\DomainEventsPublisher\EventPublisher;
use App\Core\Application\Services\DomainEventsPublisher\KafkaEventPublisher;
use App\Domains\Auth\Infrastructure\Models\User;

abstract class DomainServiceProvider extends ServiceProvider
{
    /**
     * The Domain Name.
     *
     * @var string
     */
    public string $name = '';

    /**
     * The Domain hostname.
     *
     * @var string
     */
    public string $domain = '';

    /**
     * @inheritDoc
     */
    public array $singletons = [
        CommandBusContract::class => CommandBus::class,
        QueryBusContract::class => QueryBus::class
    ];

    /**
     * The event listeners.
     *
     * @var array<string|array, string|array|callable>
     */
    public array $listeners = [];

    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    public $policies = [];

    /**
     * Register the domain services.
     *
     * @return void
     */
    public function register(): void
    {
        $domainName = Str::studly($this->name);

        $this->mergeConfigFrom(
            base_path('app/Domains/'.$domainName.'/config.php'), $this->name
        );

        EventServiceProvider::setEventDiscoveryPaths([
            base_path('app/Domains/'.$domainName.'/Application/Listeners')
        ]);
    }

    /**
     * Booting the service provider.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->domain = config($this->name.'.domain', '');

        Model::shouldBeStrict(!app()->isProduction());
        DB::prohibitDestructiveCommands(app()->isProduction());

        $this->configureRateLimiting();
        $this->registerRoutes();
        $this->registerListeners();
        $this->registerDomainListeners();
        $this->registerPolicies();

        $domainName = Str::studly($this->name);

        $this->registerBus(
            CommandBusContract::class,
            app_path("Domains/{$domainName}/Application/Commands"),
            app_path("Domains/{$domainName}/Application/Handlers/Commands")
        );
        $this->registerBus(
            QueryBusContract::class,
            app_path("Domains/{$domainName}/Application/Queries"),
            app_path("Domains/{$domainName}/Application/Handlers/Queries")
        );
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('web', function (Request $request) {
            return Limit::perMinute(500)->by($request->user()?->id ?: $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response('Too many requests.', Response::HTTP_TOO_MANY_REQUESTS, $headers);
                });
        });

        // TODO: Disable or increase the rate limit for API requests for internal calls.
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(500)->by($request->user()?->id ?: $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json(
                        [
                            'status' => ResponseStatus::ERROR,
                            'code' => Response::HTTP_TOO_MANY_REQUESTS,
                            'meta' => [
                                'requestId' => request()->header('X-Request-Id', Str::uuid()),
                                'correlationId' => request()->header('X-Correlation-Id', Str::uuid()),
                            ],
                            'message' => 'Too many requests.',
                            'data' => null,
                            
                        ],
                        Response::HTTP_TOO_MANY_REQUESTS,
                        $headers
                    );
                });
        });
    }

    /**
     * Register the domain routes.
     *
     * @return void
     */
    public function registerRoutes(): void
    {
        $this->registerRouteBindings();

        $domainName = Str::studly($this->name);

        // Route::domain($this->domain)
        Route::middleware('api')
            ->prefix('api')
            ->namespace("App\Domains\\{$domainName}\Presentation\Api\Controllers")
            ->group(base_path("app/Domains/{$domainName}/routes/api.php"));
    }

    /**
     * Register the route bindings.
     *
     * @return void
     */
    public function registerRouteBindings(): void
    {
        //
    }

    /**
     * Register the Event Listeners.
     *
     * @return void
     */
    public function registerListeners(): void
    {
        foreach ($this->listeners() as $event => $listeners) {
            if (is_array($listeners)) {
                foreach ($listeners as $listener) {
                    Event::listen($event, $listener);
                }
                continue;
            }

            Event::listen($event, $listeners);
        }
    }

    /**
     * Register the domain event listeners.
     *
     * @return void
     */
    public function registerDomainListeners(): void
    {
        $this->app->bind(EventPublisher::class, KafkaEventPublisher::class);

        Event::listen(DomainEventContract::class, DomainEventPublisher::class);
    }

    /**
     * Get the Event Listeners for the domain provider.
     *
     * @return array<class-string, class-string>
     */
    public function listeners()
    {
        return $this->listeners;
    }

    /**
     * Register the application's policies.
     *
     * @return void
     */
    public function registerPolicies()
    {
        // By default, admin users can do anything
        Gate::before(function (?User $user, $ability) {
            return $user?->isAdmin() ? true : null;
        });

        foreach ($this->policies() as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }

    /**
     * Get the policies defined on the provider.
     *
     * @return array<class-string, class-string>
     */
    public function policies()
    {
        return $this->policies;
    }

    /**
     * Register Bus
     *
     * Example call:
     * $this->registerBus(BusContract::class, $path, $handlers);
     *
     * @param string $bus
     * @param string $path
     * @param string $handlers
     *
     * @return void
     */
    public function registerBus(string $bus, string $commandsPath, string $handlersPath): void
    {
        app($bus)->map(self::getHandlersClassMap($commandsPath, $handlersPath));
    }

    /**
     * Get the class map for the given path and handlers.
     *
     * @param string $commandsPath
     * @param string $handlersPath
     * @return array
     */
    public static function getHandlersClassMap(string $commandsPath, string $handlersPath): array
    {
        $commandsNamespace = self::convertPathToNamespace($commandsPath);
        $handlersNamespace = self::convertPathToNamespace($handlersPath);

        $map = [];
        $files = File::allFiles($commandsPath);

        foreach ($files as $file) {
            $relativePath = str_replace(
                [$commandsPath, '.php', DIRECTORY_SEPARATOR], ['', '', '\\'], $file->getPathname()
            );

            $commandClass = "{$commandsNamespace}{$relativePath}";
            $handlerClass = "{$handlersNamespace}{$relativePath}Handler";

            $map[$commandClass] = $handlerClass;
        }

        return $map;
    }

    private static function convertPathToNamespace($path): string
    {
        $relativePath = str_replace(app_path(), 'App', $path);

        return str_replace([DIRECTORY_SEPARATOR, '\\'], '\\', $relativePath);
    }
}
