<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Base job class that provides logging functionality.
 */
abstract class AbstractJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Log a message with the specified level.
     *
     * @param string $level
     * @param string $message
     */
    protected function log(string $level, string $message): void
    {
        Log::channel('queue')->$level($message);
    }

    /**
     * Log an info message.
     *
     * @param string $message
     */
    protected function logInfo(string $message): void
    {
        $this->log('info', $message);
    }

    /**
     * Log a debug message.
     *
     * @param string $message
     */
    protected function logDebug(string $message): void
    {
        $this->log('debug', $message);
    }

    /**
     * Log an error message.
     *
     * @param string $message
     */
    protected function logError(string $message): void
    {
        $this->log('error', $message);
    }
}
