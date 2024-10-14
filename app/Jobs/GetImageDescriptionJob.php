<?php

namespace App\Jobs;

use App\Repositories\ImageRepository;
use App\Services\ImageService;
use App\Services\OpenAiInterface;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        $this->logInfo(
            "GetImageDescriptionJob constructed with image URL: 
            $this->imageUrl"
        );
    }

    /**
     * Handle the job.
     *
     * @param ImageService $imageService
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
                $this->logInfo("Image description OK");
            } else {
                $this->logError("No image description returned from OpenAI");
            }
        } catch (Exception $e) {
            $this->logError("Error getting image description: {$e->getMessage()}");
            throw $e;
        }
    }
}
