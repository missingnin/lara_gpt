<?php

namespace App\Jobs;

use App\Services\OpenAiInterface;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Job to get image description from OpenAI.
 */
class GetImageDescriptionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string Image URL
     */
    private string $imageUrl;

    /**
     * @var string Prompt for image description
     */
    private string $prompt;

    /**
     * Constructor.
     *
     * @param string $imageUrl Image URL
     * @param string $prompt Prompt for image description
     */
    public function __construct(string $imageUrl, string $prompt)
    {
        $this->imageUrl = $imageUrl;
        $this->prompt = $prompt;
    }

    /**
     * Handle the job.
     *
     * @param OpenAiInterface $openAiClient
     * @return void
     * @throws Exception
     */
    public function handle(OpenAiInterface $openAiClient): void
    {
        $imageDescriptionResult = $openAiClient->getImageDescription($this->imageUrl, $this->prompt);
        Log::info("Image description: {$imageDescriptionResult->getContent()}");
    }
}
