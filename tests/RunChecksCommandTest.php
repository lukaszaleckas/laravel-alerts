<?php

namespace LaravelAlerts\Tests;

use Illuminate\Console\OutputStyle;
use Illuminate\Foundation\Testing\WithFaker;
use LaravelAlerts\AlertFactory;
use LaravelAlerts\Contracts\AbstractAlert;
use LaravelAlerts\RunChecksCommand;
use LaravelAlerts\Tests\Contracts\AbstractTest;
use Mockery;
use Mockery\MockInterface;
use Symfony\Component\Console\Input\InputInterface;

class RunChecksCommandTest extends AbstractTest
{
    use WithFaker;

    /** @var MockInterface */
    private MockInterface $alertFactoryMock;

    /** @var RunChecksCommand */
    private RunChecksCommand $command;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->alertFactoryMock = Mockery::mock(AlertFactory::class);
        $this->command          = new RunChecksCommand(
            $this->alertFactoryMock
        );
    }

    /**
     * @return void
     */
    public function testRunsAlertChecks(): void
    {
        $identifiers = $this->faker->rgbColorAsArray;

        $alertMock = Mockery::mock(AbstractAlert::class);

        $alertMock->shouldReceive('runCheck')->once();
        $alertMock->shouldReceive('getIdentifier')->once()->andReturn('');

        $this->alertFactoryMock
            ->shouldReceive('buildAlerts')
            ->once()
            ->with($identifiers)
            ->andReturn([
                $alertMock
            ]);

        $this->command->setInput(
            $this->mockArgument(RunChecksCommand::ARGUMENT_IDENTIFIERS, $identifiers)
        );
        $this->command->setOutput($this->mockOutputStyle());

        self::assertEquals(0, $this->command->handle());
    }

    /**
     * @param string $name
     * @param mixed  $value
     * @return InputInterface
     */
    private function mockArgument(string $name, mixed $value): InputInterface
    {
        /** @var InputInterface */
        return Mockery::mock(InputInterface::class)
            ->shouldReceive('getArgument')
            ->with($name)
            ->once()
            ->andReturn($value)
            ->getMock();
    }

    /**
     * @return OutputStyle
     */
    private function mockOutputStyle(): OutputStyle
    {
        /** @var OutputStyle */
        return Mockery::mock(OutputStyle::class)
            ->shouldReceive('writeLn')
            ->getMock();
    }
}
