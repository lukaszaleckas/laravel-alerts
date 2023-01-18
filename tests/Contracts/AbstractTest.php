<?php

namespace LaravelAlerts\Tests\Contracts;

use LaravelAlerts\Config\ConfigKeys;
use LaravelAlerts\LaravelAlertsServiceProvider;
use Orchestra\Testbench\TestCase;

abstract class AbstractTest extends TestCase
{
    /**
     * @param mixed $app
     * @return string[]
     */
    protected function getPackageProviders(mixed $app): array
    {
        return [
            LaravelAlertsServiceProvider::class
        ];
    }

    /**
     * @param mixed $app
     * @return void
     */
    protected function defineEnvironment(mixed $app): void
    {
        $app['config']->set(
            ConfigKeys::buildFullConfigKey(ConfigKeys::DEFAULT_LOG_CHANNELS),
            []
        );
    }
}
