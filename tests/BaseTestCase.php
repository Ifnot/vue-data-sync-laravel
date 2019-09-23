<?php

namespace Ifnot\VueDataSync\Tests;

use Ifnot\VueDataSync\Providers\VueDataSyncServiceProvider;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase;

abstract class BaseTestCase extends TestCase
{
    /**
     * Get package providers.
     *
     * @param  Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [VueDataSyncServiceProvider::class];
    }
}