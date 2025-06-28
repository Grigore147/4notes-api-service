<?php

declare(strict_types=1);

namespace App\Domains\Note\Domain\Entities\Contracts;

use Carbon\Carbon;
use Ramsey\Uuid\UuidInterface;
use App\Core\Domain\Entities\EntityContract;

interface SpaceEntityContract extends EntityContract
{
    /**
     * Get the space's ID.
     *
     * @return ?UuidInterface
     */
    public function getId(): ?UuidInterface;

    /**
     * Get the user ID of the space's owner.
     *
     * @return UuidInterface
     */
    public function getUserId(): UuidInterface;

    /**
     * Get the space's name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get the space's description.
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * Get the space's creation date.
     *
     * @return ?Carbon
     */
    public function getCreatedAt(): ?Carbon;

    /**
     * Get the space's last update date.
     *
     * @return ?Carbon
     */
    public function getUpdatedAt(): ?Carbon;
}
