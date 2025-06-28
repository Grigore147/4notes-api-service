<?php

use Illuminate\Support\Str;
use App\Core\Support\Undefined;

/**
 * Convert a value to studly kebab case.
 *
 * Example: `Str::studlyKebab('foo_bar')` will return `Foo-Bar`.
 *
 * @param  string  $value
 * @return string
 */
Str::macro('studlyKebab', function (string $value): string {
    return str_replace([' ', '.'], '-', ucwords(Str::kebab($value), '-\.'));
});

/**
 * Append a prefix to a string if it doesn't already start with it.
 *
 * Example: `Str::prefix('bar', 'foo-')` will return `foo-bar`.
 *
 * @param  string  $value
 * @param  string  $prefix
 * @return string
 */
Str::macro('prefix', function (string $value, string $prefix): string {
    return Str::startsWith($value, $prefix) ? $value : $prefix.$value;
});

/**
 * Append a suffix to a string if it doesn't already end with it.
 *
 * Example: `Str::suffix('foo', '-bar')` will return `foo-bar`.
 *
 * @param  string  $value
 * @param  string  $suffix
 * @return string
 */
Str::macro('suffix', function (string $value, string $suffix): string {
    return Str::endsWith($value, $suffix) ? $value : $value.$suffix;
});
