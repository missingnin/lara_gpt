<?php

namespace App\Jobs;

use App\Repositories\ImageRepository;
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
class GetImageDescriptionJob extends AbstractJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var string Image URL
     */
    private string $imageUrl;

    /**
     * @var string imagesPrompt for image description
     */
    private string $imagesPrompt;

    /**
     * Constructor.
     *
     * @param string $imageUrl Image URL
     * @param string $imagesPrompt imagesPrompt for image description
     */
    public function __construct(string $imageUrl, string $imagesPrompt)
    {
        $this->imageUrl = $imageUrl;
        $this->imagesPrompt = $imagesPrompt;
        $this->logInfo("GetImageDescriptionJob constructed with image URL: {$this->imageUrl}");
    }

    /**
     * Handle the job.
     *
     * @param ImageRepository $imageRepository
     * @param OpenAiInterface $openAiClient
     *
     * @return void
     * @throws Exception
     */
    public function handle(ImageRepository $imageRepository, OpenAiInterface $openAiClient): void
    {
        $this->logInfo('Handling GetImageDescriptionJob...');
        try {
            $imageDescriptionResult = $openAiClient->getImageDescription(
                $this->imageUrl,
                $this->imagesPrompt
            );

            if ($imageDescriptionResult->getContent()) {
                $image = $imageRepository->findByAttribute('name', $imageDescriptionResult->getContent());
                $imageRepository->setDescription($image, $imageDescriptionResult->getContent());
                $this->logInfo("Image description set: {$imageDescriptionResult->getContent()}");
            } else {
                $this->logWarning("No image description returned from OpenAI");
            }
        } catch (Exception $e) {
            $this->logError("Error getting image description: {$e->getMessage()}");
            throw $e;
        }
    }
}