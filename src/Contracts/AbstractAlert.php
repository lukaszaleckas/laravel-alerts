<?php

namespace LaravelAlerts\Contracts;

use Illuminate\Support\Facades\Log;
use LaravelAlerts\Config\ConfigKeys;
use LaravelAlerts\DTOs\AlertResultDto;
use Monolog\Logger;

abstract class AbstractAlert
{
    /** @var string */
    private string $identifier;

    /**
     * @return void
     */
    public function runCheck(): void
    {
        $alertResultDto = $this->buildAlertResult();

        if ($alertResultDto->isTriggered()) {
            Log::stack($this->getLogChannels())
                ->getLogger()
                ->log(
                    $this->getAlertLevel(),
                    $alertResultDto->getMessage(),
                    $alertResultDto->getContext()
                );
        }
    }

    /**
     * @param array $config
     * @return void
     */
    public function configure(array $config): void
    {
    }

    /**
     * @param string $identifier
     * @return void
     */
    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return array
     */
    protected function getLogChannels(): array
    {
        /*
         * Helper used instead of DI to make this package users life easier
         * without the need to pass config repository into parent.
         */
        return config(
            ConfigKeys::buildFullConfigKey(ConfigKeys::DEFAULT_LOG_CHANNELS)
        );
    }

    /**
     * @return int
     */
    protected function getAlertLevel(): int
    {
        return Logger::ALERT;
    }

    /**
     * @return AlertResultDto
     */
    abstract protected function buildAlertResult(): AlertResultDto;
}
