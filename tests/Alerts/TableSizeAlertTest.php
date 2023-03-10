<?php

namespace LaravelAlerts\Tests\Alerts;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use LaravelAlerts\Alerts\TableSizeAlert;
use LaravelAlerts\Tests\Contracts\AbstractTest;
use LaravelAlerts\Tests\Traits\AlertTestTrait;

class TableSizeAlertTest extends AbstractTest
{
    use WithFaker;
    use AlertTestTrait;

    /** @var TableSizeAlert */
    private TableSizeAlert $alert;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->alert = new TableSizeAlert();
    }

    /**
     * @dataProvider alertCheckTestDataProvider
     *
     * @param int  $threshold
     * @param int  $count
     * @param bool $shouldTrigger
     * @return void
     */
    public function testRunsDatabaseCheck(
        int $threshold,
        int $count,
        bool $shouldTrigger
    ): void {
        $this->alert->configure([
            TableSizeAlert::CONFIG_THRESHOLD  => $threshold,
            TableSizeAlert::CONFIG_CONNECTION => $connection = $this->faker->word,
            TableSizeAlert::CONFIG_TABLE_NAME => $tableName  = $this->faker->word,
        ]);

        DB::shouldReceive('connection')->once()->with($connection)->andReturnSelf();
        DB::shouldReceive('table')->once()->with($tableName)->andReturnSelf();
        DB::shouldReceive('count')->once()->andReturn($count);

        $this->mockTriggeredAlertLog($shouldTrigger);

        $this->alert->runCheck();
    }

    /**
     * @return array
     */
    public function alertCheckTestDataProvider(): array
    {
        $this->setUp();

        $threshold = $this->faker->numberBetween();

        return [
            'Alert is triggered'     => [
                $threshold,
                $threshold + 1,
                true
            ],
            'Alert is not triggered' => [
                $threshold,
                $threshold,
                false
            ],
        ];
    }
}
