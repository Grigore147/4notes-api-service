<?php

use Illuminate\Support\Facades\File;

/**
 * Get an array of classes from a given path and namespace.
 *
 * @param  string  $path
 * @param  string  $namespace
 * @return array
 */
File::macro('loadClasses', function (string $path, string $namespace): array {
    if (!File::exists($path)) { return []; }

    return collect(File::allFiles($path))
        ->filter(fn ($file) => $file->getExtension() === 'php')
        ->map(function ($file) use ($namespace) {
            $relativePath = $file->getRelativePath();
            $relativePath = $relativePath ? str_replace(DIRECTORY_SEPARATOR, '\\', $relativePath) . '\\' : '';

            return $namespace . '\\' . $relativePath . $file->getFilenameWithoutExtension();
        })
        ->toArray();
});
