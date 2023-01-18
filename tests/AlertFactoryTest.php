<?php

namespace LaravelAlerts\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use LaravelAlerts\AlertFactory;
use LaravelAlerts\Alerts\TableSizeAlert;
use LaravelAlerts\Config\ConfigKeys;
use LaravelAlerts\Contracts\AbstractAlert;
use LaravelAlerts\Tests\Contracts\AbstractTest;

class AlertFactoryTest extends AbstractTest
{
    use WithFaker;

    public const IDENTIFIER_ALERT_1 = 'test_alert_1';
    public const IDENTIFIER_ALERT_2 = 'test_alert_2';

    /** @var AlertFactory */
    private AlertFactory $alertFactory;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->alertFactory = new AlertFactory(
            config()
        );
    }

    /**
     * @param mixed $app
     * @return void
     */
    protected function defineEnvironment(mixed $app): void
    {
        $app['config']->set(
            ConfigKeys::buildFullConfigKey(ConfigKeys::ALERTS),
            [
                $this->mockAlertConfig(self::IDENTIFIER_ALERT_1),
                $this->mockAlertConfig(self::IDENTIFIER_ALERT_2)
            ]
        );
    }

    /**
     * @dataProvider alertBuildTestDataProvider
     *
     * @param array $identifiers
     * @param int   $expectedCount
     * @return void
     */
    public function testCanBuildAlerts(array $identifiers, int $expectedCount): void
    {
        $result = $this->alertFactory->buildAlerts($identifiers);

        self::assertContainsOnlyInstancesOf(AbstractAlert::class, $result);
        self::assertCount($expectedCount, $result);
    }

    /**
     * @return array
     */
    public function alertBuildTestDataProvider(): array
    {
        $this->setUp();

        return [
            'Returns all alerts' => [
                [],
                2
            ],
            'Returns both alert' => [
                [self::IDENTIFIER_ALERT_1, self::IDENTIFIER_ALERT_2],
                2
            ],
            'Returns one alert'  => [
                $this->faker->randomElement(
                    [
                        [self::IDENTIFIER_ALERT_1],
                        [self::IDENTIFIER_ALERT_2]
                    ]
                ),
                1
            ],
            'Returns no alerts'  => [
                [
                    $this->faker->word
                ],
                0
            ],
        ];
    }

    /**
     * @param string $identifier
     * @return array
     */
    private function mockAlertConfig(string $identifier): array
    {
        return [
            ConfigKeys::ALERT      => TableSizeAlert::class,
            ConfigKeys::IDENTIFIER => $identifier,
            ConfigKeys::CONFIG     => [
                TableSizeAlert::CONFIG_CONNECTION => 'test',
                TableSizeAlert::CONFIG_TABLE_NAME => 'test_table',
                TableSizeAlert::CONFIG_THRESHOLD  => 123
            ]
        ];
    }
}
