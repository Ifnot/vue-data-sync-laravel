<?php

namespace Ifnot\VueDataSync\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ModelEvent implements ShouldBroadcast
{
    private $modelName;
    private $event;
    private $model;
    private $channel;

    private $meta;

    /**
     * Create a new ModelEvent instance.
     */
    public function __construct(string $modelName, string $event, array $model, Channel $channel = null, array $meta = [])
    {
        $this->modelName = $modelName;
        $this->event = $event;
        $this->model = $model;
        $this->channel = $channel ?? new Channel('public');

        $this->meta = $meta;
    }

    public function broadcastAs()
    {
        return 'vue-data-sync.event';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'model_name' => $this->modelName,
            'model' => $this->model,
            'event' => $this->event,
            'meta' => $this->meta,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): Channel
    {
        return $this->channel;
    }
}