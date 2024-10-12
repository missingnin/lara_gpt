<?php

namespace App\Jobs;

use App\Repositories\ImageRepository;
use App\Services\ImageServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
     * @var string Prompt for image description
     */
    private string $prompt;

    /**
     * Constructor.
     *
     * @param  string  $imageUrl  Image URL
     * @param  string  $prompt  Prompt for image description
     */
    public function __construct(string $imageUrl, string $prompt)
    {
        $this->imageUrl = $imageUrl;
        $this->prompt = $prompt;
    }

    /**
     * Execute the job.
     */
    public function handle(
        ImageServiceInterface $imageService,
        ImageRepository $imageRepository,
    ): void {
        $this->logInfo("Processing image description for URL: {$this->imageUrl}");

        if ($imageService->isImageUrlAccessible($this->imageUrl)) {
            $this->logInfo('Image URL is accessible. Dispatching GetImageDescriptionJob.');
            GetImageDescriptionJob::dispatch($this->imageUrl, $this->prompt);
        } else {
            $image = $imageRepository->findByAttribute('name', $this->imageUrl);
            if ($image) {
                $this->logInfo('Image found in repository. Setting no description.');
                $imageRepository->setNoDescription($image);
            } else {
                $this->logError("Image not found in repository for URL: {$this->imageUrl}");
            }
        }
    }
}
