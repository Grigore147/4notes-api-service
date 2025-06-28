<?php

use App\Core\Exceptions\FatalError;
use Illuminate\Foundation\Application;
use App\Core\Exceptions\ValidationException;
use App\Core\Exceptions\AuthorizationException;
use App\Core\Exceptions\ModelNotFoundException;
use Illuminate\Session\Middleware\StartSession;
use App\Core\Exceptions\AuthenticationException;
use App\Core\Exceptions\ThrottleRequestsException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Core\Presentation\Http\Middleware\EnforceJson;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use App\Core\Presentation\Http\Middleware\ContentSecurityPolicy;
use Symfony\Component\ErrorHandler\Error\FatalError as LaravelFatalError;
use Illuminate\Validation\ValidationException as LaravelValidationException;
use Illuminate\Auth\AuthenticationException as LaravelAuthenticationException;
use Illuminate\Auth\Access\AuthorizationException as LaravelAuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException as LaravelModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException as LaravelThrottleRequestsException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up'
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->removeFromGroup('web', [
            StartSession::class,
            ShareErrorsFromSession::class,
            ValidateCsrfToken::class,
        ]);

        // $middleware->appendToGroup('web', ContentSecurityPolicy::class);

        $middleware->prependToGroup('api', EnforceJson::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->map(LaravelAuthenticationException::class, AuthenticationException::class);
        $exceptions->map(LaravelAuthorizationException::class, AuthorizationException::class);
        $exceptions->map(LaravelFatalError::class, FatalError::class);
        $exceptions->map(LaravelModelNotFoundException::class, ModelNotFoundException::class);
        $exceptions->map(LaravelThrottleRequestsException::class, ThrottleRequestsException::class);
        $exceptions->map(
            LaravelValidationException::class,
            fn (LaravelValidationException $exception) => new ValidationException(
                $exception->validator, $exception->response, $exception->errorBag
            )
        );
    })->create();
