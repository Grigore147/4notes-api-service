<?php

use Carbon\Carbon;
use Tests\FeatureTestCase;
use Illuminate\Testing\TestResponse;
use App\Domains\Auth\Infrastructure\Models\User;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Space;

describe('Notes: API - Spaces', function () {
    it('doesn\'t have any space at start', function () {
        /** @var FeatureTestCase $this */

        $this->auth();

        /** @var TestResponse $response */
        $response = $this->getJson('api/spaces');

        $this->expectOk($response);

        $response->assertJson([
            'meta' => [
                'type' => 'SpaceCollection',
                'pagination' => [
                    'total' => 0
                ]
            ],
            'data' => []
        ]);
    });

    it('lists all spaces', function () {
        /** @var FeatureTestCase $this */

        /** @var User $user */
        $user = $this->auth();

        $space = Space::factory()->create(['user_id' => $user->id]);

        /** @var TestResponse $response */
        $response = $this->getJson('api/spaces');

        $this->expectOk($response);
        $this->assertCount(1, $response->json('data'));

        $response->assertJson([
            'meta' => [
                'type' => 'SpaceCollection',
                'pagination' => [
                    'from' => 1,
                    'to' => 1,
                    'total' => 1,
                    'perPage' => 25,
                    'currentPage' => 1,
                    'lastPage' => 1
                ]
            ],
            'data' => [
                [
                    'id' => $space->id,
                    'userId' => $space->user_id,
                    'name' => $space->name,
                    'description' => $space->description
                ]
            ]
        ]);
    });

    it('get a space by id', function () {
        /** @var FeatureTestCase $this */

        /** @var User $user */
        $user = $this->auth();

        $space = Space::factory()->create(['user_id' => $user->id]);

        /** @var TestResponse $response */
        $response = $this->getJson('api/spaces/'.$space->id);

        $this->expectOk($response);

        $response->assertJson([
            'meta' => [
                'type' => 'SpaceResource'
            ],
            'data' => [
                'userId' => $user->id->toString(),
                'name' => $space->name,
                'description' => $space->description,
                'createdAt' => $space->created_at->toIsoString(),
                'updatedAt' => $space->updated_at->toIsoString()
            ]
        ]);
    });

    it('cannot get a foreign space', function () {
        /** @var FeatureTestCase $this */

        $this->auth();

        $space = Space::factory()->create();

        /** @var TestResponse $response */
        $response = $this->getJson('api/spaces/'.$space->id);

        $this->expectNotFound($response);
    });

    it('creates a new space', function () {
        /** @var FeatureTestCase $this */

        /** @var User $user */
        $user = $this->auth();

        $data = [
            'name' => 'Space Name',
            'description' => 'Space Description'
        ];

        /** @var TestResponse $response */
        $response = $this->postJson('api/spaces', $data);

        $this->expectCreated($response);

        $response->assertJson([
            'meta' => [
                'type' => 'SpaceResource'
            ],
            'data' => [
                'userId' => $user->id,
                'name' => $data['name'],
                'description' => $data['description'],
                'createdAt' => Carbon::createFromTimeString($response->json('data.createdAt'))->toIsoString(),
                'updatedAt' => Carbon::createFromTimeString($response->json('data.updatedAt'))->toIsoString()
            ]
        ]);
    });

    it('updates a space', function () {
        /** @var FeatureTestCase $this */

        /** @var User $user */
        $user = $this->auth();

        $space = Space::factory()->create([
            'user_id' => $user->id,
            'name' => 'Space Name',
            'description' => 'Space Description'
        ]);
        $data = [
            'name' => 'Updated Space Name',
            'description' => 'Updated Space Description'
        ];

        /** @var TestResponse $response */
        $response = $this->putJson('api/spaces/'.$space->id, $data);

        $this->expectOk($response);

        $response->assertJson([
            'meta' => [
                'type' => 'SpaceResource'
            ],
            'data' => [
                'id' => $space->id,
                'userId' => $space->user_id,
                'name' => $data['name'],
                'description' => $data['description']
            ]
        ]);
    });

    it('cannot update a foreign space', function () {
        /** @var FeatureTestCase $this */

        $this->auth();

        $space = Space::factory()->create();

        $data = [
            'name' => 'Space Name',
            'description' => 'Space Description'
        ];

        /** @var TestResponse $response */
        $response = $this->putJson('api/spaces/'.$space->id, $data);

        $this->expectNotFound($response);
    });

    it('deletes a space', function () {
        /** @var FeatureTestCase $this */

        /** @var User $user */
        $user = $this->auth();

        $space = Space::factory()->create(['user_id' => $user->id]);

        /** @var TestResponse $response */
        $response = $this->deleteJson('api/spaces/'.$space->id);

        $this->expectNoContent($response);

        /** @var TestResponse $response */
        $response = $this->getJson('api/spaces/'.$space->id);

        $this->expectNotFound($response);
    });

    it('cannot delete a foreign space', function () {
        /** @var FeatureTestCase $this */

        $this->auth();

        $space = Space::factory()->create();

        /** @var TestResponse $response */
        $response = $this->deleteJson('api/spaces/'.$space->id);

        $this->expectNotFound($response);
    });
});
