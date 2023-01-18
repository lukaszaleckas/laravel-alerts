<?php

use LaravelAlerts\Alerts\TableSizeAlert;
use LaravelAlerts\Config\ConfigKeys;

return [
    ConfigKeys::DEFAULT_LOG_CHANNELS => explode(
        ',',
        env('LARAVEL_ALERTS_DEFAULT_LOG_CHANNELS', 'stack')
    ),
    ConfigKeys::ALERTS               => [
        [
            ConfigKeys::ALERT      => TableSizeAlert::class,
            ConfigKeys::IDENTIFIER => 'failed_jobs_alert',
            ConfigKeys::CONFIG     => [
                TableSizeAlert::CONFIG_CONNECTION => 'mysql',
                TableSizeAlert::CONFIG_TABLE_NAME => 'failed_jobs',
                TableSizeAlert::CONFIG_THRESHOLD  => 30
            ]
        ]
    ]
];
