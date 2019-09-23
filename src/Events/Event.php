<?php

namespace Ifnot\VueDataSync\Events;

use Ifnot\VueDataSync\Contracts\Event as EventContract;
use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class Event implements EventContract
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Create a new Event instance.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'vue-data-sync.event';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|Channel[]
     */
    public function broadcastOn()
    {
        return new Channel('public');
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'model_name' => $this->modelName(),
            'model' => $this->model,
            'meta' => $this->meta(),
        ];
    }

    /**
     * @return void
     */
    public function send(): void
    {
        event($this);
    }

    /**
     * @return array
     */
    abstract public function meta(): array;

    /**
     * @return string
     */
    protected function modelName(): string
    {
        $baseClassName = class_basename($this->model);

        $snakeCase = Str::snake($baseClassName);

        return Str::singular($snakeCase);
    }
}