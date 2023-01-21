<?php

namespace LaravelAlerts\Alerts;

use Illuminate\Queue\QueueManager;
use LaravelAlerts\Contracts\AbstractAlert;
use LaravelAlerts\DTOs\AlertResultDto;

class QueueSizeAlert extends AbstractAlert
{
    public const CONFIG_CONNECTION = 'connection';
    public const CONFIG_QUEUE      = 'queue';
    public const CONFIG_THRESHOLD  = 'threshold';

    /** @var string */
    private string $connection;

    /** @var string */
    private string $queue;

    /** @var string */
    private string $threshold;

    /**
     * @param QueueManager $queueManager
     */
    public function __construct(private QueueManager $queueManager)
    {
    }

    /**
     * @param array $config
     * @return void
     */
    public function configure(array $config): void
    {
        $this->connection = $config[self::CONFIG_CONNECTION];
        $this->queue      = $config[self::CONFIG_QUEUE];
        $this->threshold  = $config[self::CONFIG_THRESHOLD];
    }

    /**
     * @return AlertResultDto
     */
    protected function buildAlertResult(): AlertResultDto
    {
        $queueSize = $this->getQueueSize();

        return new AlertResultDto(
            $this->isAlertTriggered($queueSize),
            $this->getMessage(),
            $this->buildContext($queueSize)
        );
    }

    /**
     * @return int
     */
    private function getQueueSize(): int
    {
        return $this->queueManager
            ->connection($this->connection)
            ->size($this->queue);
    }

    /**
     * @param int $queueSize
     * @return bool
     */
    private function isAlertTriggered(int $queueSize): bool
    {
        return $queueSize > $this->threshold;
    }

    /**
     * @return string
     */
    private function getMessage(): string
    {
        return sprintf(
            "'%s' queue size exceeds %s job threshold!",
            $this->queue,
            $this->threshold
        );
    }

    /**
     * @param int $queueSize
     * @return array
     */
    private function buildContext(int $queueSize): array
    {
        return [
            'connection' => $this->connection,
            'queue_name' => $this->queue,
            'queue_size' => $queueSize,
            'threshold'  => $this->threshold
        ];
    }
}
