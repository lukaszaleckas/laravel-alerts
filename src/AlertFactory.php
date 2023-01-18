<?php

namespace LaravelAlerts;

use Illuminate\Config\Repository;
use LaravelAlerts\Config\ConfigKeys;
use LaravelAlerts\Contracts\AbstractAlert;

class AlertFactory
{
    /** @var array */
    private array $alertsConfig;

    /**
     * @param Repository $configRepository
     */
    public function __construct(Repository $configRepository)
    {
        $this->alertsConfig = $configRepository->get(
            ConfigKeys::buildFullConfigKey(ConfigKeys::ALERTS),
            []
        );
    }

    /**
     * @param array $identifiers
     * @return AbstractAlert[]
     */
    public function buildAlerts(array $identifiers = []): array
    {
        $result = [];

        foreach ($this->alertsConfig as $alertConfig) {
            $alertIdentifier = $alertConfig[ConfigKeys::IDENTIFIER];

            if (!empty($identifiers) && !in_array($alertIdentifier, $identifiers)) {
                continue;
            }

            $result[] = $this->buildAlert(
                $alertConfig[ConfigKeys::ALERT],
                $alertConfig[ConfigKeys::IDENTIFIER],
                $alertConfig[ConfigKeys::CONFIG] ?? []
            );
        }

        return $result;
    }

    /**
     * @param string $alertClassName
     * @param string $identifier
     * @param array  $config
     * @return AbstractAlert
     */
    private function buildAlert(string $alertClassName, string $identifier, array $config): AbstractAlert
    {
        /** @var AbstractAlert $alert */
        $alert = app($alertClassName);

        $alert->setIdentifier($identifier);

        $alert->configure($config);

        return $alert;
    }
}
