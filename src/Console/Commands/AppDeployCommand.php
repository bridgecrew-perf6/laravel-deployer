<?php

namespace Bakle\LaravelDeployer\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Yaml\Yaml;

class AppDeployCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:deploy {deployment-name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploy the application according app-deploy.yml';

    private array $yaml;

    public function handle(): int
    {
        try {
            $this->setFile();
        } catch (Exception $exception) {
            Log::error('Laravel-Deployer: File not found', [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            $this->error('File does not exist');

            return self::FAILURE;
        }

        $deploymentName = $this->argument('deployment-name');

        if (!$deploymentName) {
            $deployment = Arr::first($this->yaml['deployments']);
        } else {
            $deployment = $this->getDeploymentByName($deploymentName);
        }

        if (!$deployment) {
            Log::error('Laravel-Deployer: Deployment ' . $deploymentName . ' not found');

            $this->error('The given deployment does not exist');

            return self::FAILURE;
        }

        $this->executeDeploymentSteps($deployment);

        $this->info('App deployed successfully');

        return self::SUCCESS;
    }

    private function getDeploymentByName(string $name): ?array
    {
        return Arr::first(array_filter($this->yaml['deployments'], function ($item) use ($name) {
            return $item['name'] === $name;
        }));
    }

    private function executeDeploymentSteps(array $deployment): void
    {
        foreach ($deployment['steps'] as $step) {
            Artisan::call($step, [], $this->output);
        }
    }

    private function setFile(): void
    {
        $this->yaml = Yaml::parse(file_get_contents(config('laravel-deployer.file')));
    }
}
