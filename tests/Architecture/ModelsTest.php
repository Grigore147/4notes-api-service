<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;

describe('Notes: Models', function () {
    arch('should have isolated models', function () {
        $models = File::loadClasses(
            base_path('app/Domains/Note/Infrastructure/Database/Eloquent/Models'),
            'App\Domains\Note\Infrastructure\Database\Eloquent\Models'
        );
    
        foreach ($models as $model) {
            expect($model)->toExtend(Model::class);
            expect($model)
                ->toOnlyBeUsedIn([
                    'App\Domains\Note\Infrastructure\Database',
                    'App\Domains\Note\Infrastructure\Repositories',
                    'App\Domains\Note\Application\Mappers'
                ]);
        }
    });
});
