<?php

use Tests\FeatureTestCase;
use Illuminate\Support\Facades\Event;
use App\Domains\Auth\Infrastructure\Models\User;
use App\Domains\Note\Application\Services\NoteService;
use App\Domains\Note\Domain\Entities\Note as NoteEntity;
use App\Domains\Note\Domain\Events\Note\NoteCreated;
use App\Domains\Note\Domain\Events\Note\NoteDeleted;
use App\Domains\Note\Domain\Events\Note\NoteUpdated;
use App\Domains\Note\Domain\Repositories\NotesRepository;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Note;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Notebook;

describe('Notes: Notes Repository', function () {
    it('creates a new note', function () {
        Event::fake();

        $notebook = Notebook::factory()->create();
        $data = [
            'userId' => $notebook->user_id,
            'notebookId' => $notebook->id,
            'title' => 'Note Title',
            'content' => 'Note Content'
        ];

        $noteService = app(NoteService::class);

        $note = $noteService->create(NoteEntity::fromArray($data));

        expect($note)->toBeValidNote($data);

        Event::assertDispatchedTimes(NoteCreated::class, 1);
        Event::assertDispatched(NoteCreated::class, function ($event) use ($note, $data) {
            return expect($event->note)->toMatchArray([
                'id' => $note->getId(),
                ...$data
            ]);
        });
    });

    it('updates a note', function () {
        Event::fake();

        $notebook = Notebook::factory()->create();
        $data = [
            'userId' => $notebook->user_id,
            'notebookId' => $notebook->id,
            'title' => 'Note Title',
            'content' => 'Note Content'
        ];
        $updatedData = [
            'title' => 'Updated Note Title',
            'content' => 'Updated Note Content'
        ];

        $noteService = app(NoteService::class);

        $note = $noteService->create(NoteEntity::fromArray($data));

        $updatedNote = $noteService->update($note, $updatedData);

        expect($updatedNote)->toBeValidNote([
            ...$data,
            ...$updatedData
        ]);

        Event::assertDispatchedTimes(NoteUpdated::class, 1);
        Event::assertDispatched(NoteUpdated::class, function ($event) use ($note, $data, $updatedData) {
            return expect($event->note)->toMatchArray([
                'id' => $note->getId(),
                ...$data,
                ...$updatedData
            ]);
        });
    });

    it('deletes a note', function () {
        Event::fake();

        $notebook = Notebook::factory()->create();
        $note = Note::factory()->create([
            'user_id' => $notebook->user_id,
            'notebook_id' => $notebook->id
        ]);

        $noteService = app(NoteService::class);

        $noteDeleted = $noteService->delete($note->id);

        expect($noteDeleted)->toBeTrue();

        Event::assertDispatchedTimes(NoteDeleted::class, 1);
        Event::assertDispatched(NoteDeleted::class, function ($event) use ($note) {
            return (string)$event->note->getId() === (string)$note->id;
        });
    });
});
