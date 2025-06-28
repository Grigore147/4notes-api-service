<?php

declare(strict_types=1);

namespace App\Domains\Note\Application\Commands\Stack;

use Ramsey\Uuid\UuidInterface;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Core\Application\CommandBus\Command;
use App\Domains\Note\Domain\Entities\Contracts\StackEntityContract;

final class UpdateStack extends Command
{
    public function __construct(
        /**
         * User
         *
         * @var Authenticatable $user
         */
        public ?Authenticatable $user,

        /**
         * Stack ID
         *
         * @var UuidInterface $id
         */
        public UuidInterface $id,

        /**
         * Updated Stack Entity
         *
         * @var StackEntityContract $stack
         */
        public StackEntityContract $stack,

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
            id: $request->stack->getId(),
            stack: $request->stack->setAttributes($request->validated())
        );
    }
}
