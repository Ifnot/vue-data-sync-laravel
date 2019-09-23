<?php

namespace Ifnot\VueDataSync\Facades;

use Illuminate\Support\Facades\Facade;

class VueSync extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'vuesync';
    }
}