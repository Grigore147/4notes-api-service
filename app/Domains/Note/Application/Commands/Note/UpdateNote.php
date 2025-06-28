<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Commands\Note;

use Ramsey\Uuid\UuidInterface;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Core\Application\CommandBus\Command;
use App\Domains\Note\Domain\Entities\Contracts\NoteEntityContract;

final class UpdateNote extends Command
{
    public function __construct(
        /**
         * User
         *
         * @var Authenticatable $user
         */
        public ?Authenticatable $user,

        /**
         * Note ID
         *
         * @var UuidInterface $id
         */
        public UuidInterface $id,

        /**
         * Updated Note Entity
         *
         * @var NoteEntityContract $note
         */
        public NoteEntityContract $note,

        /**
         * Stack update data
         *
         * @var array $data
         */
        public array $data = []
    ) {}

    /**
     * Create a new command instance from a request.
     *
     * @param  Request  $request
     * @return static
     */
    public static function fromRequest(Request $request): static
    {
        return new static(
            user: $request->user(),
            id: $request->note->getId(),
            note: $request->note->setAttributes($request->validated())
        );
    }
}
