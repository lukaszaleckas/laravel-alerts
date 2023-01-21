<?php

namespace LaravelAlerts\Tests\Traits;

use Illuminate\Support\Facades\Log;

trait AlertTestTrait
{
    /**
     * @param bool $shouldLog
     * @return void
     */
    public function mockTriggeredAlertLog(bool $shouldLog): void
    {
        Log::shouldReceive('stack')->andReturnSelf();
        Log::shouldReceive('getLogger')->andReturnSelf();
        Log::shouldReceive('log')->times($shouldLog ? 1 : 0);
    }
}
