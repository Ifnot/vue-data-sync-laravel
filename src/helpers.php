<?php
use Illuminate\Database\Eloquent\Model;

if(!function_exists('model_event')) {
    function model_event(Model $model, $name, array $meta = [])
    {
        \Ifnot\VueDataSync\ModelBroadcaster::fire($model, $name, $meta);
    }
}