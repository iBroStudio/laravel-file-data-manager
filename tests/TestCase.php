<?php

namespace IBroStudio\FileDataManager\Tests;

use IBroStudio\FileDataManager\FileDataManagerServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            FileDataManagerServiceProvider::class,
        ];
    }
}
