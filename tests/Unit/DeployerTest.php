<?php

namespace Bakle\LaravelDeployer\Tests\Unit;

use Bakle\LaravelDeployer\Tests\TestCase;

class DeployerTest extends TestCase
{
    /**
     * @test
     */
    public function itCanDeploySpecificStep()
    {
        $this->artisan('app:deploy clear-cache')
            ->expectsOutput('Application cache cleared!')
            ->expectsOutput('App deployed successfully')
            ->assertExitCode(0);
    }

    /**
     * @test
     */
    public function itCanDeployFirstStepAsDefault()
    {
        $this->artisan('app:deploy')
            ->expectsOutput('Compiled views cleared!')
            ->expectsOutput('App deployed successfully')
            ->assertExitCode(0);
    }

    /**
     * @test
     */
    public function itCannotDeployNonexistentStep()
    {
        $this->artisan('app:deploy app')
            ->expectsOutput('The given deployment does not exist')
            ->assertExitCode(1);
    }

    /**
     * @test
     */
    public function itCannotDeployNonexistentFile()
    {
        $this->app['config']->set('laravel-deployer.file', 'test');
        $this->artisan('app:deploy app')
            ->expectsOutput('File does not exist')
            ->assertExitCode(1);
    }
}
