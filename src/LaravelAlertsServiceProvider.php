<?php

namespace LaravelAlerts;

use Illuminate\Support\ServiceProvider;

class LaravelAlertsServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $this->publishConfig();

        $this->registerCommand();
    }

    /**
     * @return void
     */
    private function publishConfig(): void
    {
        $this->publishes(
            [
                __DIR__ . '/Config/laravel-alerts.php' => config_path('laravel-alerts.php'),
            ],
            'laravel-alerts'
        );
    }

    /**
     * @return void
     */
    private function registerCommand(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                RunChecksCommand::class
            ]);
        }
    }
}
