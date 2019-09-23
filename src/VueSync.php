<?php

namespace Ifnot\VueDataSync;

use Ifnot\VueDataSync\Contracts\Observer;
use Ifnot\VueDataSync\Contracts\Synchronizer as SynchronizerInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class VueSync
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var string[]|array
     */
    protected $synced = [];

    /**
     * VueData constructor.
     *
     * @param Application|null $app
     */
    public function __construct(Application $app = null)
    {
        $this->app = $app;
    }

    /**
     * @param string $class
     * @param string|null $synchronizer
     * @return void
     * @throws BindingResolutionException
     */
    public function sync(string $class, ?string $synchronizer = null): void
    {
        $model = $this->instantiateModel($class);
        $observer = $this->instantiateModelObserver(Observer::class);

        $model::observe($observer);

        $this->synced[$class] = $synchronizer ?? SynchronizerInterface::class;
    }

    /**
     * @param string|Model $model
     * @return SynchronizerInterface
     * @throws BindingResolutionException
     */
    public function synchronizerFor($model): SynchronizerInterface
    {
        $class = is_object($model) ? get_class($model) : $model;

        if (! array_key_exists($class, $this->synced)) {
            throw new InvalidArgumentException("Synchronizer for model [{$class}] not found.");
        }

        return $this->instantiateSynchronizer($this->synced[$class], $class);
    }

    /**
     * @return SynchronizerInterface[]|array
     */
    public function synchronizers(): array
    {
        return $this->synced;
    }

    /**
     * @return string[]|array
     */
    public function models(): array
    {
        return array_keys($this->synced);
    }

    /**
     * @param string $class
     * @return Observer
     * @throws BindingResolutionException
     */
    private function instantiateModelObserver(string $class): Observer
    {
        return $this->app->make($class);
    }

    /**
     * @param string|null $class
     * @param string|Model $model
     * @return SynchronizerInterface
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function instantiateSynchronizer(string $class, $model): SynchronizerInterface
    {
        $model = ! is_object($model) ? $this->app->make($model) : $model;

        return $this->app->make($class, ['model' => $model]);
    }

    /**
     * @param string $class
     * @return Model
     * @throws BindingResolutionException
     */
    private function instantiateModel(string $class): Model
    {
        return $this->app->make($class);
    }
}