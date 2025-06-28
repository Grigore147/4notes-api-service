<?php

declare(strict_types=1);

namespace App\Core\Presentation\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as LaravelController;
use App\Core\Presentation\Http\Traits\SendsResponses;
use App\Core\Application\QueryBus\Contracts\QueryBus;
use App\Core\Application\CommandBus\Contracts\CommandBus;

abstract class Controller extends LaravelController // @pest-arch-ignore-line
{
    use AuthorizesRequests;
    use ValidatesRequests;
    use DispatchesJobs;
    use SendsResponses;

    public function __construct(
        protected readonly CommandBus $commandBus,
        protected readonly QueryBus $queryBus
    ) {}
}
