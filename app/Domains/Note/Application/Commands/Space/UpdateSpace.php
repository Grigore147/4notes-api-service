<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Commands\Space;

use Carbon\Carbon;
use Ramsey\Uuid\UuidInterface;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Core\Application\CommandBus\Command;

final class UpdateSpace extends Command
{
    public function __construct(
        /**
         * User
         *
         * @var Authenticatable $user
         */
        public ?Authenticatable $user,
        
        /**
         * Space ID
         *
         * @var UuidInterface $id
         */
        public UuidInterface $id,

        /**
         * User ID
         *
         * @var UuidInterface $userId
         */
        public UuidInterface $userId,

        /**
         * Space name
         *
         * @var string $name
         */
        public string $name = '',

        /**
         * Space description
         *
         * @var string $description
         */
        public string $description = '',

        /**
         * Space created at
         *
         * @var Carbon $createdAt
         */
        public ?Carbon $createdAt = null,
        
        /**
         * Space updated at
         *
         * @var Carbon $updatedAt
         */
        public ?Carbon $updatedAt = null
    ) {}

    /**
     * Create a new command instance from a request.
     *
     * @param  Request  $request
     * @return static
     */
    public static function fromRequest(Request $request): static
    {
        return static::fromArray([
            ...$request->validated(),
            'user' => $request->user(),
            'id' => $request->space->getId(),
            'userId' => $request->user()->id
        ]);
    }
}
