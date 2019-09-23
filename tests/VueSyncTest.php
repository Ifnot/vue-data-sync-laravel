<?php

namespace Ifnot\VueDataSync\Tests;

use Ifnot\VueDataSync\Contracts\Observer;
use Ifnot\VueDataSync\Contracts\Synchronizer as SynchronizerInterface;
use Ifnot\VueDataSync\Transport\Synchronizer;
use Ifnot\VueDataSync\VueSync;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Mockery\MockInterface;

class VueSyncTest extends BaseTestCase
{
    /**
     * @throws BindingResolutionException
     */
    public function testSyncedModelObservesAModelObserver(): void
    {
        /** @var SyncedModel|MockInterface $mockSyncedModel */
        $mockSyncedModel = $this->mock(SyncedModel::class);

        /** @var Observer|MockInterface $mockObserver */
        $mockObserver = $this->mock(Observer::class);

        /** @var VueSync $vueSync */
        $vueSync = $this->app->make(VueSync::class);

        $mockSyncedModel->shouldReceive('observe')->once()->with($mockObserver);

        $vueSync->sync(SyncedModel::class);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testItReturnsDefaultSynchronizerForGivenClassWhenNotSpecified(): void
    {
        /** @var SyncedModel $syncedModel */
        $syncedModel = $this->instance(SyncedModel::class, new SyncedModel);

        /** @var VueSync $vueSync */
        $vueSync = $this->app->make(VueSync::class);

        /** @var SynchronizerInterface|MockInterface $defaultSynchronizer */
        $defaultSynchronizer = $this->instance(SynchronizerInterface::class, $this->app->make(SynchronizerInterface::class, ['model' => $syncedModel]));

        $vueSync->sync(SyncedModel::class);

        $synchronizer = $vueSync->synchronizerFor($syncedModel);

        $this->assertEquals($defaultSynchronizer, $synchronizer);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testItReturnsCorrectSynchronizerForGivenClassWhenSpecified()
    {
        /** @var SyncedModel $syncedModel */
        $syncedModel = $this->instance(SyncedModel::class, new SyncedModel);

        /** @var VueSync $vueSync */
        $vueSync = $this->app->make(VueSync::class);

        $expectedSynchronizer = $this->instance(TestSynchronizer::class, $this->app->make(TestSynchronizer::class, ['model' => $syncedModel]));

        $vueSync->sync(SyncedModel::class, TestSynchronizer::class);

        $synchronizer = $vueSync->synchronizerFor(SyncedModel::class);

        $this->assertEquals($expectedSynchronizer, $synchronizer);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testItThrowsAnExceptionWhenTheGivenModelIsNotSynced()
    {
        /** @var VueSync $vueSync */
        $vueSync = $this->app->make(VueSync::class);

        $this->expectException(InvalidArgumentException::class);

        $vueSync->synchronizerFor(SyncedModel::class);
    }
}

class SyncedModel extends Model
{
    //
}

class TestSynchronizer extends Synchronizer
{
    //
}