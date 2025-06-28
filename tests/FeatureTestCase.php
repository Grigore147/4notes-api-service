<?php

declare(strict_types=1);

namespace Tests;

use Tests\TestCase;
use Tests\Traits\Authenticates;
use Tests\Traits\AssertsResponses;

/**
 * @inheritDoc
 * @extends parent
 *
 * @method public assertDatabaseHas($table, array $data = [], $connection = null);
 * @method public assertDatabaseMissing($table, array $data = [], $connection = null);
 */
abstract class FeatureTestCase extends TestCase
{
    use Authenticates, AssertsResponses;
}
