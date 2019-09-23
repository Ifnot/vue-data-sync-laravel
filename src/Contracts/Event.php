<?php

namespace Ifnot\VueDataSync\Contracts;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

interface Event extends ShouldBroadcast
{
    /**
     * @return string
     */
    public function broadcastAs(): string;

    /**
     * @return array
     */
    public function broadcastWith(): array;

    /**
     * @return array
     */
    public function meta(): array;

    /**
     * @return void
     */
    public function send(): void;
}