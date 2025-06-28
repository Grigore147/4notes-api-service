<?php

declare(strict_types=1);

namespace App\Core\Support\Enums;

use App\Core\Support\Enums\Traits\EnumValues;

/**
 * Value types for various data types.
 */
enum ValueTypes: string
{
    use EnumValues;

    case STRING = 'string';
    case INTEGER = 'integer';
    case FLOAT = 'float';
    case BOOLEAN = 'boolean';
    case ARRAY = 'array';
    case OBJECT = 'object';
    case CALLABLE = 'callable';
    case RESOURCE = 'resource';
    case NULL = 'null';
    case UNDEFINED = 'undefined';
}
