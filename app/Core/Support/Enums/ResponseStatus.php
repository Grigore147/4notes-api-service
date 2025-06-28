<?php

declare(strict_types=1);

namespace App\Core\Support\Enums;

use App\Core\Support\Enums\Traits\EnumValues;

/**
 * Response status for API responses.
 */
enum ResponseStatus: string
{
    use EnumValues;

    case SUCCESS = 'success';
    case ERROR = 'error';
}
