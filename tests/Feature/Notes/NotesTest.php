<?php

use Carbon\Carbon;
use Tests\FeatureTestCase;
use Illuminate\Testing\TestResponse;
use App\Domains\Auth\Infrastructure\Models\User;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Note;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Space;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Stack;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Notebook;

describe('Notes: API - Notes', function () {
    it('doesn\'t have any note at start', function () {
        /** @var FeatureTestCase $this */

        $this->auth();

        /** @var TestResponse $response */
        $response = $this->getJson('api/notes');

        $this->expectOk($response);

        $response->assertJson([
            'meta' => [
                'type' => 'NoteCollection',
                'pagination' => [
                    'total' => 0
                ]
            ],
            'data' => []
        ]);
    });

    it('lists all notes', function () {
        /** @var FeatureTestCase $this */

        /** @var User $user */
        $user = $this->auth();

        $note = Note::factory()->create(['user_id' => $user->id]);

        /** @var TestResponse $response */
        $response = $this->getJson('api/notes');

        $this->expectOk($response);
        $this->assertCount(1, $response->json('data'));

        $response->assertJson([
            'meta' => [
                'type' => 'NoteCollection',
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
                    'id' => $note->id,
                    'userId' => $note->user_id,
                    'notebookId' => $note->notebook_id,
                    'title' => $note->title,
                    'content' => $note->content
                ]
            ]
        ]);
    });

    it('gets a note by id', function () {
        /** @var FeatureTestCase $this */

        /** @var User $user */
        $user = $this->auth();

        $note = Note::factory()->create(['user_id' => $user->id]);

        /** @var TestResponse $response */
        $response = $this->getJson('api/notes/'.$note->id);

        $this->expectOk($response);

        $response->assertJson([
            'meta' => [
                'type' => 'NoteResource'
            ],
            'data' => [
                'userId' => $user->id,
                'notebookId' => $note->notebook_id,
                'title' => $note->title,
                'content' => $note->content,
                'createdAt' => $note->created_at->toIsoString(),
                'updatedAt' => $note->updated_at->toIsoString()
            ]
        ]);
    });

    it('cannot get a foreign note', function () {
        /** @var FeatureTestCase $this */

        $this->auth();

        $note = Note::factory()->create();

        /** @var TestResponse $response */
        $response = $this->getJson('api/notes/'.$note->id);

        $this->expectNotFound($response);
    });

    it('creates a new note', function () {
        /** @var FeatureTestCase $this */

        /** @var User $user */
        $user = $this->auth();

        $space = Space::factory()->create(['user_id' => $user->id]);
        $stack = Stack::factory()->create([
            'user_id' => $user->id,
            'space_id' => $space->id
        ]);
        $notebook = Notebook::factory()->create([
            'user_id' => $user->id,
            'space_id' => $space->id,
            'stack_id' => $stack->id
        ]);

        $data = [
            'notebookId' => $notebook->id,
            'title' => 'Note Title',
            'content' => 'Note Content'
        ];

        /** @var TestResponse $response */
        $response = $this->postJson('api/notes', $data);

        $this->expectCreated($response);

        $response->assertJson([
            'meta' => [
                'type' => 'NoteResource'
            ],
            'data' => [
                'userId' => $user->id,
                'notebookId' => $data['notebookId'],
                'title' => $data['title'],
                'content' => $data['content'],
                'createdAt' => Carbon::createFromTimeString($response->json('data.createdAt'))->toIsoString(),
                'updatedAt' => Carbon::createFromTimeString($response->json('data.updatedAt'))->toIsoString()
            ]
        ]);
    });

    it('cannot create a note within a foreign notebook', function () {
        /** @var FeatureTestCase $this */

        $this->auth();

        $notebook = Notebook::factory()->create();

        $data = [
            'notebookId' => $notebook->id,
            'title' => 'Note Title',
            'content' => 'Note Content'
        ];

        /** @var TestResponse $response */
        $response = $this->postJson('api/notes', $data);

        $this->expectUnprocessableEntity($response);
    });

    it('updates a note', function () {
        /** @var FeatureTestCase $this */

        /** @var User $user */
        $user = $this->auth();

        $note = Note::factory()->create([
            'user_id' => $user->id,
            'title' => 'Note Name',
            'content' => 'Note Content'
        ]);
        $data = [
            'title' => 'Updated Note Name',
            'content' => 'Updated Note Content'
        ];
        
        /** @var TestResponse $response */
        $response = $this->putJson('api/notes/'.$note->id, $data);

        $this->expectOk($response);

        $response->assertJson([
            'meta' => [
                'type' => 'NoteResource'
            ],
            'data' => [
                'id' => $note->id,
                'userId' => $note->user_id,
                'notebookId' => $note->notebook_id,
                'title' => $data['title'],
                'content' => $data['content']
            ]
        ]);
    });

    it('cannot update a foreign note', function () {
        /** @var FeatureTestCase $this */

        $this->auth();

        $note = Note::factory()->create();

        $data = [
            'title' => 'Updated Note Name',
            'content' => 'Updated Note Content'
        ];

        /** @var TestResponse $response */
        $response = $this->putJson('api/notes/'.$note->id, $data);

        $this->expectNotFound($response);
    });

    it('deletes a note', function () {
        /** @var FeatureTestCase $this */

        /** @var User $user */
        $user = $this->auth();

        $note = Note::factory()->create(['user_id' => $user->id]);

        /** @var TestResponse $response */
        $response = $this->deleteJson('api/notes/'.$note->id);

        $this->expectNoContent($response);

        /** @var TestResponse $response */
        $response = $this->getJson('api/notes/'.$note->id);

        $this->expectNotFound($response);
    });

    it('cannot delete a foreign note', function () {
        /** @var FeatureTestCase $this */

        $this->auth();

        $note = Note::factory()->create();

        /** @var TestResponse $response */
        $response = $this->deleteJson('api/notes/'.$note->id);

        $this->expectNotFound($response);
    });
});
