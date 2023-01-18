<?php

namespace LaravelAlerts\DTOs;

class AlertResultDto
{
    /**
     * @param bool   $isTriggered
     * @param string $message
     * @param array  $context
     */
    public function __construct(
        private bool $isTriggered,
        private string $message,
        private array $context = []
    ) {
    }

    /**
     * @return bool
     */
    public function isTriggered(): bool
    {
        return $this->isTriggered;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }
}
