<?php

namespace LaravelAlerts\Tests\Alerts;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Queue\QueueManager;
use LaravelAlerts\Alerts\QueueSizeAlert;
use LaravelAlerts\Tests\Contracts\AbstractTest;
use LaravelAlerts\Tests\Traits\AlertTestTrait;
use Mockery;
use Mockery\MockInterface;

class QueueSizeAlertTest extends AbstractTest
{
    use WithFaker;
    use AlertTestTrait;

    /** @var QueueSizeAlert */
    private QueueSizeAlert $alert;

    /** @var MockInterface */
    private MockInterface $queueManagerMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->queueManagerMock = Mockery::mock(QueueManager::class);
        $this->alert            = new QueueSizeAlert(
            $this->queueManagerMock
        );
    }

    /**
     * @dataProvider alertCheckTestDataProvider
     *
     * @param int  $threshold
     * @param int  $size
     * @param bool $shouldTrigger
     * @return void
     */
    public function testRunsQueueSizeCheck(
        int $threshold,
        int $size,
        bool $shouldTrigger
    ): void {
        $this->alert->configure([
            QueueSizeAlert::CONFIG_THRESHOLD  => $threshold,
            QueueSizeAlert::CONFIG_CONNECTION => $connection = $this->faker->word,
            QueueSizeAlert::CONFIG_QUEUE      => $queue      = $this->faker->word,
        ]);

        $this->queueManagerMock
            ->shouldReceive('connection')
            ->once()
            ->with($connection)
            ->andReturnSelf();

        $this->queueManagerMock
            ->shouldReceive('size')
            ->once()
            ->andReturn($size);

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
