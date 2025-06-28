<?php

use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as HttpRequest;
use App\Core\Presentation\Request\RequestQuery;

/**
 * Get the requested fields from an API Request
 *
 * @param string $defaultResource The default resource name to use
 * @param array $defaultFieldsets The default fieldsets to use
 *
 * @return array The requested fields set
 */
Request::macro('getRequestedFields', function ($defaultResource = 'default', $defaultFieldsets=['*']): array {
    /** @var HttpRequest */
    $request = request();
    
    /** @disregard P1014 */
    $request->requestQuery ??= RequestQuery::fromRequest(request());

    return $request->requestQuery->getRequestedFields($defaultResource, $defaultFieldsets);
});
