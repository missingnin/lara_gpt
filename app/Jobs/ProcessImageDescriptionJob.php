<?php

namespace App\Jobs;

use App\Models\Image;
use App\Repositories\ImageRepository;
use App\Services\ImageServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class ProcessImageDescriptionJob
 *
 * This job processes the image description for a given image URL and imagesPrompt.
 */
class ProcessImageDescriptionJob extends AbstractJob implements ShouldQueue
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
     * @param string $imagesPrompt
     */
    public function __construct(string $imageUrl, string $imagesPrompt)
    {
        $this->imageUrl = $imageUrl;
        $this->imagesPrompt = $imagesPrompt;
        $this->logInfo(
            "Job constructed with image URL: 
            {$this->imageUrl} 
            and imagesPrompt: {$this->imagesPrompt}"
        );
    }

    /**
     * Execute the job.
     */
    public function handle(ImageServiceInterface $imageService, ImageRepository $imageRepository): void
    {
        $this->logInfo('Handling image description processing...');
        $image = $imageRepository->findByAttribute('name', $this->imageUrl);

        if ($image) {
            if ($imageRepository->imageNeedDescription($this->imagesPrompt, $image)) {
                $this->logInfo('Image needs description. Processing...');
                $this->processImageDescription($image, $imageService, $imageRepository);
            } else {
                $this->logInfo('Image already has description. Skipping.');
            }
        }
    }

    /**
     * Process the image description.
     *
     * @param Image $image The image object
     * @param ImageServiceInterface $imageService The image service instance
     * @param ImageRepository $imageRepository The image repository instance
     */
    private function processImageDescription(
        Image $image,
        ImageServiceInterface $imageService,
        ImageRepository $imageRepository
    ): void {
        $this->logInfo('Processing image description...');
        if ($imageService->isImageUrlAccessible($this->imageUrl)) {
            $this->logInfo(
                'Image URL is accessible. Dispatching GetImageDescriptionJob.'
            );
            GetImageDescriptionJob::dispatch($this->imageUrl, $this->imagesPrompt);
        } else {
            $this->logInfo('Image URL is not accessible. Handling inaccessible image...');
            $this->handleInaccessibleImage($image, $imageRepository);
        }
    }

    /**
     * Handle the inaccessible image.
     *
     * @param Image $image The image object (maybe null)
     * @param ImageRepository $imageRepository The image repository instance
     */
    private function handleInaccessibleImage(
        Image $image,
        ImageRepository $imageRepository
    ): void {
        $this->logInfo('Image found in repository, but URL Inaccessible - "No Description"');
        $imageRepository->setDescription($image);
    }
}
