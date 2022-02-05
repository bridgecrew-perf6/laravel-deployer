<?php

namespace Bakle\LaravelDeployer\Tests;

use Bakle\LaravelDeployer\LaravelDeployerServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [LaravelDeployerServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('laravel-deployer.file', __DIR__ . '/Stubs/app-deployment.yml');
    }
}
