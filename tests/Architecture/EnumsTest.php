<?php

declare(strict_types=1);

arch('enums')
    ->expect('*Enums*')
    ->toBeEnums()
    ->toExtendNothing()
    ->toUseNothing()
    ->toHaveMethod('toArray');

// ->toBeStringBackedEnums();
