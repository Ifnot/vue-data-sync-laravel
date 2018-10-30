# VUE DATA SYNC - WIP

Realtime model synchronization between VueJS and Laravel Eloquent Models.

This package allows you to send eloquent events (create, update and delete) through laravel echo to your VueJs app in order to keep all your clients data in sync with your laravel backend.

This package is designed for an **easy integration without deep changes of your backend and frontend**.

## Prerequisites

> You should have a working Echo installation. [Please follow the official installation steps from the documentation](https://laravel.com/docs/5.7/broadcasting).

## Installation

    composer require ifnot/vue-data-sync

As it is a WIP, you may want lower your stability options in your `composer.json` :

    "minimum-stability": "dev",
    "prefer-stable": true

Then add the service provider into your `config/app.php` **before your Application Service Providers (important)** :

    Ifnot\VueDataSync\Providers\VueDataSyncServiceProvider::class

## Quick Start

Listen your eloquent models for modifications into your `AppServiceProvider` :

```php
use Ifnot\VueDataSync\VueData;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        VueData::sync([
            App\Car::class
        ]);
    }
}
```

Now, you can [setup the client side (VueJs)](https://github.com/Ifnot/vue-data-sync)

## Fine tuning

Reference a `$synchronizer` class into your model property for overloading default behaviour :

```php
class Car extends Model
{
    public $synchronizer = CarSynchronizer::class;
    
    // [...]
}

class CarSynchronizer extends Ifnot\VueDataSync\Vuex\ModelSynchronizer
{
    /**
     * Return the related models witch should be updated when this model
     * is updated / deleted
     */
    public function getCascadeRelations(): array
    
    /**
     * Return the frontend name of the model (VueJS side)
     */
    public function getName(): string
    
    /**
     * Return the channels names to be broadcasted, if false or empty, no
     * message will be sent.
     */
    public function getChannels()
    
    /**
     * Transform the model object to array in order to be serialized on the
     * broadcast event.
     */
    public function toArray(Model $model): array
    
    /*
     * Return the meta to be broadcasted with the message
     */
    public function getMeta()
    
    /*
     * Handle the event emitting
     */
    public function emit(string $event, array $meta = [])
}
```

> Refer to the default behaviour by [looking at ModelSynchronizer](https://github.com/Ifnot/vue-data-sync-laravel/blob/master/src/Model/Transport/ModelSynchronizer.php)
