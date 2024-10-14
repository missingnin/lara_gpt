<?php

namespace App\Listeners;

use App\Events\ImageDescriptionUpdated;
use App\Events\ProductImagesGotDescription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

/**
 * Listener for the ImageDescriptionUpdated event.
 */
class CheckProductImagesDescription implements ShouldQueue
{
    /**
     * Handle the ImageDescriptionUpdated event.
     *
     * @param ImageDescriptionUpdated $event The event instance.
     *
     * @return void
     */
    public function handle(ImageDescriptionUpdated $event): void
    {
        Log::info(
            "Handling ImageDescriptionUpdated event for product ID: 
            $event->productId"
        );

        if ($event->percentage === 100) {
            Log::info(
                "Product ID: 
                $event->productId 
                has reached 100% image description completion"
            );
            event(new ProductImagesGotDescription($event->productId));
        }
    }
}
