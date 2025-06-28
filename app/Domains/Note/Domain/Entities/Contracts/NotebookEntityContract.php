<?php

declare(strict_types=1);

namespace App\Domains\Note\Domain\Entities\Contracts;

use Carbon\Carbon;
use Ramsey\Uuid\UuidInterface;
use App\Core\Domain\Entities\EntityContract;

interface NotebookEntityContract extends EntityContract
{
    /**
     * Get the notebook's user ID.
     *
     * @return UuidInterface|string|null
     */
    public function getUserId(): UuidInterface|string|null;

    /**
     * Get the notebook's space ID.
     *
     * @return UuidInterface|string|null
     */
    public function getSpaceId(): UuidInterface|string|null;

    /**
     * Get the notebook's space.
     *
     * @return mixed
     */
    public function getSpace(): mixed;

    /**
     * Get the notebook's stack ID.
     *
     * @return UuidInterface|string|null
     */
    public function getStackId(): UuidInterface|string|null;

    /**
     * Get the notebook's stack.
     *
     * @return mixed
     */
    public function getStack(): mixed;

    /**
     * Get the notebook's name.
     *
     * @return string
     */
    public function getName(): string;
}
