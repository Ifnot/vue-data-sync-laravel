<?php

namespace Ifnot\VueDataSync\Vuex;

use Ifnot\VueDataSync\Helper;
use Ifnot\VueDataSync\Events\ModelEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Model;

class ModelSynchronizer
{
    protected $class;

    protected $model;

    public function __construct(Model $model)
    {
        $this->class = get_class($model);
        $this->model = $model;
    }

    /**
     * Return the related models witch should be updated when this model
     * is updated / deleted
     */
    public function getCascadeRelations(): array
    {
        return [];
    }

    /**
     * Return the frontend name of the model (VueJS side)
     */
    public function getName(): string
    {
        $baseClassName = basename(str_replace('\\', '/', $this->class));
        return str_singular(snake_case($baseClassName));
    }

    /**
     * Return the channels names to be broadcasted, if false or empty, no
     * message will be sent.
     */
    public function getChannels()
    {
        return [new Channel('public')];
    }

    /**
     * Transform the model object to array in order to be serialized on the
     * broadcast event.
     */
    public function toArray(Model $model): array
    {
        return $model->toArray();
    }

    /*
     * Return the meta to be broadcasted with the message
     */
    public function getMeta()
    {
        return [];
    }

    /*
     * Handle the event emitting
     */
    public function emit(string $event, array $meta = [])
    {
        // If the broadcast is an update, check the channel creations / deletions
        if ($event === 'update') {
            $this->handleUpdateEmit($meta);
        } else {
            // Create and delete are simpler, just broadcast
            $channels = $this->getChannels();
            if (is_array($channels)) {
                foreach ($channels as $channel) {
                    $this->emitBroadcastEvent($event, $channel, $meta);
                }
            }
        }

        // Broadcast related models events
        foreach ($this->getRelatedSynchronizers() as $synchronizer) {
            $synchronizer->emit('update', [
                'vue_data_sync' => [
                    'from' => [
                        'model_name' => $this->getName(),
                        'model' => $this->toArray($this->model),
                        'event' => $event,
                    ],
                ],
            ]);
        }
    }

    private function handleUpdateEmit($meta)
    {
        $originalChannels = Helper::getModelSynchronizer($this->getOriginal())->getChannels();
        $channels = $this->getChannels();

        $originalChannelWithKey = [];
        array_walk($originalChannels, function ($channel) use (&$originalChannelWithKey) {
            $originalChannelWithKey[$channel->__toString()] = $channel;
        });
        $channelsWithKey = [];
        array_walk($channels, function ($channel) use (&$channelsWithKey) {
            $channelsWithKey[$channel->__toString()] = $channel;
        });

        foreach ($originalChannelWithKey as $name => $channel) {
            // If the original channel is not present on new channel, delete the model
            if (!array_key_exists($name, $channelsWithKey)) {
                $this->emitBroadcastEvent('delete', $channel, $meta);
            }
        }

        foreach ($channelsWithKey as $name => $channel) {
            // If the new channel is not present on original, create the model
            if (!array_key_exists($name, $originalChannelWithKey)) {
                $this->emitBroadcastEvent('create', $channel, $meta);
            } else {
                $this->emitBroadcastEvent('update', $channel, $meta);
            }
        }
    }

    protected function getRelatedSynchronizers(): array
    {
        $synchronizers = [];

        foreach (array_merge(Helper::getCascadeRelatedModels($this->model), Helper::getCascadeRelatedModels($this->getOriginal())) as $relatedModel) {
            $synchronizers[] = Helper::getModelSynchronizer($relatedModel);
        }

        return $synchronizers;
    }

    private function getOriginal()
    {
        return (clone $this->model)->fill($this->model->getOriginal());
    }

    private function emitBroadcastEvent($event, $channel, $meta)
    {
        event(new ModelEvent($this->getName(), $event, $this->toArray($this->model), $channel, array_merge($this->getMeta(), $meta)));
    }
}
