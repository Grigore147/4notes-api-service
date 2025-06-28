<?php

use Tests\FeatureTestCase;
use Illuminate\Testing\TestResponse;

describe('Auth: Guest', function () {
    it('cannot access private api', function () {
        /** @var FeatureTestCase $this */

        /** @var TestResponse $response */
        $response = $this->getJson('api/user');

        $this->expectUnauthorized($response);
    });

    it('cannot access private web', function () {
        /** @var FeatureTestCase $this */

        /** @var TestResponse $response */
        $response = $this->get('dashboard');

        $this->expectRedirect($response);
    });
});
