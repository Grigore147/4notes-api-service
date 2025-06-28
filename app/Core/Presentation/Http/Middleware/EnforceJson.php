<?php

declare(strict_types=1);

namespace App\Core\Presentation\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

final class EnforceJson
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
