<?php

declare(strict_types=1);

arch('jobs')
    ->expect('App\Core\Application\Jobs')
    ->toHaveMethod('handle')
    ->toHaveConstructor()
    ->toExtendNothing()
    ->toImplement('Illuminate\Contracts\Queue\ShouldQueue');
