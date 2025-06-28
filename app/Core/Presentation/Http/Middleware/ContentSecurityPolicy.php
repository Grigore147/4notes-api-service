<?php

declare(strict_types=1);

namespace App\Core\Presentation\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class ContentSecurityPolicy
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
        /** @var Response $response */
        $response = $next($request);

        if ($cspHeader = $this->cspHeader($request)) {
            $response->headers->set('Content-Security-Policy', $cspHeader);
        }

        return $response;
    }

    /**
     * Get the CSP header that should be applied for the request.
     *
     * @param Request $request
     *
     * @return ?string
     */
    protected function cspHeader(Request $request): ?string
    {
        // If the request is for the Telescope assets, we don't apply a CSP header.
        $path = $request->path();

        if (preg_match('/^(telescope|pulse).*$/', $path)) {
            return null;
        }

        // Default CSP header for the application.
        return "default-src 'self' http: https: data: blob: 'unsafe-inline'";
    }
}
