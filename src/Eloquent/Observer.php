<?php

namespace Ifnot\VueDataSync\Eloquent;

use Ifnot\VueDataSync\Contracts\Observer as ObserverInterface;
use Illuminate\Database\Eloquent\Model;

class Observer implements ObserverInterface
{
    /**
     * @param Model $model
     * @return mixed
     */
    public function created(Model $model)
    {
        // TODO: Implement created() method.
    }

    /**
     * @param Model $model
     * @return mixed
     */
    public function updated(Model $model)
    {
        // TODO: Implement updated() method.
    }

    /**
     * @param Model $model
     * @return mixed
     */
    public function deleted(Model $model)
    {
        // TODO: Implement deleted() method.
    }
}
