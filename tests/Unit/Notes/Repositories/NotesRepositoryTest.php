<?php

use Tests\FeatureTestCase;
use App\Domains\Auth\Infrastructure\Models\User;
use App\Domains\Note\Domain\Entities\Note as NoteEntity;
use App\Domains\Note\Domain\Repositories\NotesRepository;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Note;
use App\Domains\Note\Infrastructure\Database\Eloquent\Models\Notebook;

describe('Notes: Notes Repository', function () {
    it('creates a new note', function () {
        /** @var FeatureTestCase $this */

        $notebook = Notebook::factory()->create();
        $data = [
            'userId' => $notebook->user_id,
            'notebookId' => $notebook->id,
            'title' => 'Note Title',
            'content' => 'Note Content'
        ];

        $noteRepository = app(NotesRepository::class);

        $note = $noteRepository->create(NoteEntity::fromArray($data));

        expect($note)->toBeValidNote($data);

        $this->assertDatabaseHas('notes', [
            'id' => $note->getId(),
            'user_id' => $notebook->user_id,
            'notebook_id' => $notebook->id,
            'title' => $data['title'],
            'content' => $data['content']
        ]);
    });

    it('updates a note', function () {
        /** @var FeatureTestCase $this */

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

        $noteRepository = app(NotesRepository::class);

        $note = $noteRepository->create(NoteEntity::fromArray($data));

        $updatedNote = $noteRepository->update($note, $updatedData);

        expect($updatedNote)->toBeValidNote([
            ...$data,
            ...$updatedData
        ]);
        expect($updatedNote->getUserId())->toEqual($notebook->user_id);
        expect($updatedNote->getNotebookId())->toEqual($notebook->id);
        expect($updatedNote->getTitle())->toBe($updatedData['title']);
        expect($updatedNote->getContent())->toBe($updatedData['content']);

        $this->assertDatabaseHas('notes', [
            'id' => $note->getId(),
            'user_id' => $notebook->user_id,
            'notebook_id' => $notebook->id,
            'title' => $updatedData['title'],
            'content' => $updatedData['content']
        ]);
    });

    it('updates a note from entity', function () {
        /** @var FeatureTestCase $this */

        $notebook = Notebook::factory()->create();
        $data = [
            'userId' => $notebook->user_id,
            'notebookId' => $notebook->id,
            'title' => 'Note Title',
            'content' => 'Note Content'
        ];
        $updateData = [
            'title' => 'Updated Note Title',
            'content' => 'Updated Note Content'
        ];

        $noteRepository = app(NotesRepository::class);

        /** @var NoteEntity $note */
        $note = $noteRepository->create(NoteEntity::fromArray($data));

        $note->set(
            title: $updateData['title'],
            content: $updateData['content']
        );

        $updatedNote = $noteRepository->update($note);

        expect($updatedNote)->toBeValidNote([
            ...$data,
            ...$updateData
        ]);
        expect($updatedNote->getUserId())->toEqual($notebook->user_id);
        expect($updatedNote->getNotebookId())->toEqual($notebook->id);
        expect($updatedNote->getTitle())->toBe($updateData['title']);
        expect($updatedNote->getContent())->toBe($updateData['content']);

        $this->assertDatabaseHas('notes', [
            'id' => $note->getId(),
            'user_id' => $notebook->user_id,
            'notebook_id' => $notebook->id,
            'title' => $updateData['title'],
            'content' => $updateData['content']
        ]);
    });

    it('deletes a note', function () {
        /** @var FeatureTestCase $this */

        $notebook = Notebook::factory()->create();
        $note = Note::factory()->create([
            'user_id' => $notebook->user_id,
            'notebook_id' => $notebook->id
        ]);

        $noteRepository = app(NotesRepository::class);

        $noteDeleted = $noteRepository->delete($note->id);

        expect($noteDeleted)->toBeTrue();

        $this->assertDatabaseMissing('notes', [
            'id' => $note->id
        ]);
    });

    it('finds a note by ID', function () {
        /** @var FeatureTestCase $this */

        $notebook = Notebook::factory()->create();
        $data = [
            'userId' => $notebook->user_id,
            'notebookId' => $notebook->id,
            'title' => 'Note Title',
            'content' => 'Note Content'
        ];

        $notesRepository = app(NotesRepository::class);

        $note = $notesRepository->create(NoteEntity::fromArray($data));

        $foundNote = app(NotesRepository::class)->findById($note->getId());

        expect($note)->toMatchArray($foundNote->toArray());
    });

    it('gets all notes', function () {
        /** @var FeatureTestCase $this */

        Note::factory()->count(3)->create();

        $noteRepository = app(NotesRepository::class);

        $notes = $noteRepository->all();

        expect($notes)->toHaveCount(3);
    });

    it('gets all notes by user ID', function () {
        /** @var FeatureTestCase $this */

        $user = User::factory()->create();
        $user2 = User::factory()->create();

        Note::factory()->count(3)->create(['user_id' => $user->id]);
        Note::factory()->count(2)->create(['user_id' => $user2->id]);

        $noteRepository = app(NotesRepository::class);

        $notes = $noteRepository->find(['userId' => $user->id]);

        expect($notes)->toHaveCount(3);
    });
});
