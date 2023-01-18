<?php

namespace LaravelAlerts\Config;

class ConfigKeys
{
    public const CONFIG_NAME = 'laravel-alerts';

    public const ALERTS               = 'alerts';
    public const ALERT                = 'alert';
    public const IDENTIFIER           = 'identifier';
    public const CONFIG               = 'config';
    public const DEFAULT_LOG_CHANNELS = 'default_log_channels';

    /**
     * @param string $name
     * @return string
     */
    public static function buildFullConfigKey(string $name): string
    {
        return sprintf('%s.%s', self::CONFIG_NAME, $name);
    }
}
