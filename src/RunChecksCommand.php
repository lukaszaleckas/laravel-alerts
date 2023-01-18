<?php

namespace LaravelAlerts;

use Illuminate\Console\Command;

class RunChecksCommand extends Command
{
    public const ARGUMENT_IDENTIFIERS = 'identifiers';

    /** @var string */
    protected $signature = 'laravel-alerts:run-checks {identifiers?*}';

    /** @var string */
    protected $description = 'Run checks and alert in log channel(s) if triggered';

    /**
     * @param AlertFactory $alertFactory
     */
    public function __construct(private AlertFactory $alertFactory)
    {
        parent::__construct();
    }

    /**
     * @return int
     */
    public function handle(): int
    {
        $alerts = $this->alertFactory->buildAlerts(
            $this->getIdentifiersArgument()
        );

        foreach ($alerts as $alert) {
            $this->info("Running {$alert->getIdentifier()} alert check...");

            $alert->runCheck();
        }

        $this->info('Checks completed!');

        return self::SUCCESS;
    }

    /**
     * @return array
     */
    private function getIdentifiersArgument(): array
    {
        return $this->argument(self::ARGUMENT_IDENTIFIERS);
    }
}
