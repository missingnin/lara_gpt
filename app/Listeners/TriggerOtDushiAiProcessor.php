<?php

namespace App\Listeners;

use App\Constants\OtDushiAiProcessTypes;
use App\Events\ProductImagesGotDescription;
use App\Exceptions\InvalidProcessTypeException;
use App\Services\OtDushiAi\Processors\OtDushiAiProcessor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * Listener for the ProductImagesGotDescription event.
 */
class TriggerOtDushiAiProcessor implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param ProductImagesGotDescription $event
     * @param OtDushiAiProcessor $processor
     *
     * @return void
     * @throws InvalidProcessTypeException
     */
    public function handle(
        ProductImagesGotDescription $event,
    ): void {
        Log::info(
            "Triggering OtDushi AI processor for product ID: 
            $event->productId"
        );

        $processor = app(OtDushiAiProcessor::class);

        $data = [
            'product_id' => $event->productId,
        ];

        try {
            $processor->process(
                $data,
                OtDushiAiProcessTypes::GET_AI_SPREADS_GROUPS
            );
            Log::info(
                "OtDushi AI processor triggered successfully for product ID: 
                $event->productId"
            );
        } catch (InvalidProcessTypeException $e) {
            Log::error(
                "Error triggering OtDushi AI processor for product ID: 
                $event->productId: 
                {$e->getMessage()}"
            );
            throw $e;
        }
    }
}
