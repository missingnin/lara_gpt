<?php

namespace App\Listeners;

use App\Events\ImageDescriptionUpdatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Listener for the ImageDescriptionUpdatedEvent.
 */
class LogImageDescriptionUpdated implements ShouldQueue
{
    /**
     * Handle the ImageDescriptionUpdatedEvent.
     *
     * @param ImageDescriptionUpdatedEvent $event The event instance.
     *
     * @return void
     */
    public function handle(ImageDescriptionUpdatedEvent $event): void
    {
        $event->log();
    }
}
