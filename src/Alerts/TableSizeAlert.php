<?php

namespace LaravelAlerts\Alerts;

use Illuminate\Support\Facades\DB;
use LaravelAlerts\Contracts\AbstractAlert;
use LaravelAlerts\DTOs\AlertResultDto;

class TableSizeAlert extends AbstractAlert
{
    public const CONFIG_CONNECTION = 'connection';
    public const CONFIG_TABLE_NAME = 'table_name';
    public const CONFIG_THRESHOLD  = 'threshold';

    /** @var string|null */
    private ?string $connection;

    /** @var string */
    private string $tableName;

    /** @var int */
    private int $threshold;

    /**
     * @param array $config
     * @return void
     */
    public function configure(array $config): void
    {
        $this->connection = $config[self::CONFIG_CONNECTION];
        $this->tableName  = $config[self::CONFIG_TABLE_NAME];
        $this->threshold  = $config[self::CONFIG_THRESHOLD];
    }

    /**
     * @return AlertResultDto
     */
    protected function buildAlertResult(): AlertResultDto
    {
        return new AlertResultDto(
            $this->isAlertTriggered(),
            $this->buildMessage()
        );
    }

    /**
     * @return bool
     */
    private function isAlertTriggered(): bool
    {
        return DB::connection($this->connection)
            ->table($this->tableName)
            ->count() > $this->threshold;
    }

    /**
     * @return string
     */
    private function buildMessage(): string
    {
        return sprintf(
            "%s table's size exceeded %s threshold!",
            $this->tableName,
            $this->threshold
        );
    }
}
