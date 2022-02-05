<?php

namespace Bakle\LaravelDeployer;

use Bakle\LaravelDeployer\Console\Commands\AppDeployCommand;
use Illuminate\Support\ServiceProvider;

class LaravelDeployerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadCommands();
        $this->loadConfig();
    }

    /**
     * It loads the config files from the package's
     * config path.
     */
    private function loadConfig(): void
    {
        $configPath = __DIR__ . '/../config/laravel-deployer.php';

        $this->publishes([
            $configPath => config_path('laravel-deployer.php'),
        ], 'laravel-deployer');
    }

    /**
     * It loads the commands from the package's
     * commands path.
     */
    private function loadCommands(): void
    {
        $this->commands([
            AppDeployCommand::class,
        ]);
    }
}
