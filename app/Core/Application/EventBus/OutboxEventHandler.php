<?php

declare(strict_types=1);

namespace App\Core\Application\EventBus;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Queue\InteractsWithQueue;
use App\Core\Application\EventBus\Contracts\OutboxEventHandler as OutboxEventHandlerContract;

/**
 * OutboxEventHandler
 *
 * @package App\EventListeners
 */
abstract class OutboxEventHandler implements OutboxEventHandlerContract, ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The name of the connection the job should be sent to.
     *
     * @var string|null
     */
    public $connection = 'outbox';

    /**
     * The time (seconds) before the job should be processed.
     *
     * @var int
     */
    public $delay = 0;

    /**
     * The number of times the queued listener may be attempted.
     *
     * @var int
     */
    public $retries = 5;
    
    /**
     * The number of seconds to wait before retrying the queued listener.
     *
     * @var int
     */
    public $backoff = 3;
    
    /**
     * Get the name of the listener's queue.
     */
    public function viaQueue(): string
    {
        return config('queue.connections.outbox.queue');
    }
}
