<?php

namespace Ifnot\VueDataSync\Transport;

use Ifnot\VueDataSync\Contracts\Event;
use Ifnot\VueDataSync\Contracts\Synchronizer as SynchronizerInterface;

class Synchronizer implements SynchronizerInterface
{
    /**
     * @param Event $event
     *
     * @return void
     */
    public function emit(Event $event): void
    {
        // TODO: Implement emit() method.
    }

    /**
     * @return SynchronizerInterface|array
     */
    public function relatedSynchronizers(): array
    {
        // TODO: Implement relatedSynchronizers() method.
    }
}
