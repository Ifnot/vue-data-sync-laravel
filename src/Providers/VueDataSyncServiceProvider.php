<?php

namespace Ifnot\VueDataSync\Providers;

use Ifnot\VueDataSync\Contracts\Observer as ObserverInterface;
use Ifnot\VueDataSync\Contracts\Synchronizer as SynchronizerInterface;
use Ifnot\VueDataSync\Eloquent\Observer;
use Ifnot\VueDataSync\Transport\Synchronizer;
use Ifnot\VueDataSync\VueSync;
use Illuminate\Support\ServiceProvider;

class VueDataSyncServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        include __DIR__.'/../helpers.php';
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ObserverInterface::class, Observer::class);
        $this->app->bind(SynchronizerInterface::class, Synchronizer::class);

        $this->app->singleton(VueSync::class);

        $this->app->alias(VueSync::class, 'vuesync');
    }
}
