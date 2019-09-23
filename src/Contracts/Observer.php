<?php

namespace Ifnot\VueDataSync\Contracts;

use Illuminate\Database\Eloquent\Model;

interface Observer
{
    /**
     * @param Model $model
     * @return mixed
     */
    public function created(Model $model);

    /**
     * @param Model $model
     * @return mixed
     */
    public function updated(Model $model);

    /**
     * @param Model $model
     * @return mixed
     */
    public function deleted(Model $model);
}