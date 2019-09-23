<?php

namespace Ifnot\VueDataSync\Contracts;

interface Synchronizer
{
    /**
     * @param Event $event
     *
     * @return void
     */
    public function emit(Event $event): void;

    /**
     * @return Synchronizer[]|array
     */
    public function relatedSynchronizers(): array;
}