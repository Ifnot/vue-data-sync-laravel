<?php

namespace Ifnot\VueDataSync;

use Ifnot\VueDataSync\Vuex\ModelSynchronizer;

class ModelBroadcaster
{
    public static $ignored = [];

    public static function fire($model, $event, array $meta = [])
    {
        if(!self::isIgnored(get_class($model))) {
            $synchronizer = $model->synchronizer ? new $model->synchronizer($model) : new ModelSynchronizer($model);
            $synchronizer->emit($event, $meta);
        }
    }

    public static function ignore(string $class)
    {
        self::$ignored[] = $class;
    }

    public static function isIgnored(string $class)
    {
        foreach(self::$ignored as $ignored) {
            if(fnmatch($ignored, $class)) {
                return true;
            }
        }

        return false;
    }
}