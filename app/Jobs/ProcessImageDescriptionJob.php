<?php

namespace App\Jobs\OtDushiAi;

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
     * @var OpenAiInterface OpenAI client interface
     */
    private OpenAiInterface $openAiClient;

    /**
     * Constructor.
     *
     * @param string $imageUrl Image URL
     * @param string $prompt Prompt for image description
     * @param OpenAiInterface $openAiClient OpenAI client interface
     */
    public function __construct(string $imageUrl, string $prompt, OpenAiInterface $openAiClient)
    {
        $this->imageUrl = $imageUrl;
        $this->prompt = $prompt;
        $this->openAiClient = $openAiClient;
    }

    /**
     * Handle the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle(): void
    {
        $imageDescriptionResult = $this->openAiClient->getImageDescription($this->imageUrl, $this->prompt);
        Log::info("Image description: {$imageDescriptionResult->getContent()}");
    }
}

