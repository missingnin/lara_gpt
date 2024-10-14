<?php

namespace App\Listeners;

use App\Constants\OtDushiAiProcessTypes;
use App\Events\ProductImagesGotDescriptionEvent;
use App\Exceptions\InvalidProcessTypeException;
use App\Services\OtDushiAi\Processors\OtDushiAiProcessor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Listener for the ProductImagesGotDescriptionEvent.
 */
class TriggerOtDushiAiProcessor implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param ProductImagesGotDescriptionEvent $event
     * @param OtDushiAiProcessor $processor
     *
     * @return void
     * @throws InvalidProcessTypeException
     */
    public function handle(
        ProductImagesGotDescriptionEvent $event,
        OtDushiAiProcessor $processor
    ): void {
        $data = [
            'product_id' => $event->productId,
        ];

        $processor->process(
            $data,
            OtDushiAiProcessTypes::GET_AI_SPREADS_GROUPS
        );
    }
}
