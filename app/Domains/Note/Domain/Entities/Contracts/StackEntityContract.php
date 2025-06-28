<?php

declare(strict_types=1);

namespace App\Domains\Note\Domain\Entities\Contracts;

use Carbon\Carbon;
use Ramsey\Uuid\UuidInterface;
use App\Core\Domain\Entities\EntityContract;

interface StackEntityContract extends EntityContract
{
    /**
     * Get the stack's ID.
     *
     * @return ?UuidInterface
     */
    public function getId(): ?UuidInterface;

    /**
     * Get the user ID of the stack's owner.
     *
     * @return UuidInterface|string
     */
    public function getUserId(): UuidInterface|string;

    /**
     * Get the space ID of the stack's parent space.
     *
     * @return UuidInterface|string|null
     */
    public function getSpaceId(): UuidInterface|string|null;

    /**
     * Get the stack's space.
     *
     * @return mixed
     */
    public function getSpace(): mixed;

    /**
     * Get the stack's name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get the stack's creation date.
     *
     * @return ?Carbon
     */
    public function getCreatedAt(): ?Carbon;

    /**
     * Get the stack's last update date.
     *
     * @return ?Carbon
     */
    public function getUpdatedAt(): ?Carbon;
}
