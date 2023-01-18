# Laravel Alerts

## Installation

1. Run:

```
composer require lukaszaleckas/laravel-alerts
```

Service provider should be automatically registered, if not add

```php
LaravelAlerts\LaravelAlertsServiceProvider::class
```

to your application's `app.php`.

2. Publish `laravel-alerts.php` config file:

```
    php artisan vendor:publish --tag=laravel-alerts
```

3. Schedule `RunChecksCommand` in your `App\Console\Kernel` `schedule` method:

```php
    use LaravelAlerts\RunChecksCommand;

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(RunChecksCommand::class)
            ->everyMinute()
            ->withoutOverlapping();
    }
```

In the example above, we are running all of the checks registered in config
file, every minute.

To run only specific alert checks, use the example below:

```php
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(
            RunChecksCommand::class,
            [
                RunChecksCommand::ARGUMENT_IDENTIFIERS => [
                    'first_alert',
                    'second_alert'
                ]
            ]
        )
            ->everyMinute()
            ->withoutOverlapping();
    }
```

## Creating alerts

Alert can be created using these steps:

1. Create a new class anywhere in you project.
2. Extend it from `LaravelAlerts\Contracts\AbstractAlert`:

```php
class MySuperDuperAlert extends AbstractAlert
{
    ...
```

3. Add required method `buildAlertResult` which should return
`LaravelAlerts\DTOs\AlertResultDto` DTO. This DTO contains a boolean
if alert was triggered, alert message and any additional context which
will be appended to the alert's log.

4. Next, we need to register this alert in the config files `alerts` array.
Below is an example of a `failed_jobs` table row count alert configuration:

```php
[
    ConfigKeys::ALERT      => TableSizeAlert::class,
    ConfigKeys::IDENTIFIER => 'failed_jobs_alert',
    ConfigKeys::CONFIG     => [
        TableSizeAlert::CONFIG_CONNECTION => 'mysql',
        TableSizeAlert::CONFIG_TABLE_NAME => 'failed_jobs',
        TableSizeAlert::CONFIG_THRESHOLD  => 30
    ]
]
```

### Additional alert customization

#### Configuration parameters

To make alerts reusable, they can require configuration parameters.
If you want to use this feature:

1. Add `ConfigKeys::CONFIG` in you `laravel-alerts.php`
alerts' configuration and specify any number of parameters.
(See an example above).
2. Override `LaravelAlerts\Contracts\AbstractAlert` `configure` method,
which will receive an array containing all of the parameters you added
in the first step.

#### Logging options

Log level and log channels can be configured by overriding `LaravelAlerts\Contracts\AbstractAlert`
classes `getAlertLevel`and `getLogChannels` methods respectively.

## Available alerts

As of writing this documentation, this package contains `TableSizeAlert`
which can be used to check if table's row count did not exceed configured threshold.
