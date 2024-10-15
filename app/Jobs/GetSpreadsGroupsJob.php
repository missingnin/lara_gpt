<?php

namespace App\Jobs;

use App\Services\ProductServiceInterface;
use App\Services\OpenAiInterface;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Job to get spreads groups from OpenAI.
 */
class GetSpreadsGroupsJob extends AbstractJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var array Images with descriptions
     */
    private array $imagesWithDescription;

    /**
     * @var int Product ID
     */
    private int $spreadsPrompt;

    /**
     * Constructor.
     *
     * @param array $imagesWithDescription Images with descriptions
     * @param int $spreadsPrompt           Product ID
     */
    public function __construct(array $imagesWithDescription, int $spreadsPrompt)
    {
        $this->imagesWithDescription = $imagesWithDescription;
        $this->spreadsPrompt = $spreadsPrompt;
        $this->logInfo(
            "GetSpreadsGroupsJob constructed with product ID: 
            $this->spreadsPrompt"
        );
    }

    /**
     * Handle the job.
     *
     * @param ProductServiceInterface $productService
     * @param OpenAiInterface $openAiClient
     *
     * @return void
     * @throws Exception
     */
    public function handle(ProductServiceInterface $productService, OpenAiInterface $openAiClient): void
    {
        $this->logInfo('Handling GetSpreadsGroupsJob...');
        try {
            $spreadsGroupsResult = $openAiClient->getSpreadsGroups(
                $this->imagesWithDescription,
                $this->spreadsPrompt
            );

            if ($spreadsGroupsResult->getContent()) {
                dd($spreadsGroupsResult->getContent());
                $this->logInfo("Spreads groups OK");
            } else {
                $this->logError("No spreads groups returned from OpenAI");
            }
        } catch (Exception $e) {
            $this->logError("Error getting spreads groups: {$e->getMessage()}");
            throw $e;
        }
    }
}
