<?php

declare(strict_types=1);

namespace App\Core\Support\Enums\Traits;

trait EnumValues
{
    public static function keys(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value', 'name');
    }
}
