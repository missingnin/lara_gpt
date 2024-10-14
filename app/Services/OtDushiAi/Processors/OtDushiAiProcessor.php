<?php

namespace App\Services\OtDushiAi\Processors;

use App\Constants\OtDushiAiProcessTypes;
use App\Exceptions\InvalidProcessTypeException;
use App\Jobs\ProcessImageDescriptionJob;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Services\ImageService;
use App\Services\ImageServiceInterface;
use Illuminate\Support\Collection;
use InvalidArgumentException;

/**
 * Class for OtDushi AI processors
 */
class OtDushiAiProcessor
{
    /**
     * @var ImageService
     */
    private ImageServiceInterface $imageService;

    private ProductRepository $productRepository;

    /**
     * Constructor
     */
    public function __construct(
        ImageServiceInterface $imageService,
        ProductRepository $productRepository
    ) {
        $this->imageService = $imageService;
        $this->productRepository = $productRepository;
    }

    /**
     * Process data based on the process type
     *
     * @param  mixed  $data
     *
     * @throws InvalidProcessTypeException
     */
    public function process(array $data, int $processType): void
    {
        match ($processType) {
            OtDushiAiProcessTypes::GET_AI_IMAGES_DESCRIPTION => $this->processImageDescription($data),
            default => throw new InvalidProcessTypeException($processType),
        };
    }

    /**
     * Process image description
     */
    private function processImageDescription(array $data): void
    {
        if (
            !isset(
                $data['images'],
                $data['images_prompt'],
                $data['images_prompt'],
                $data['data_id']
            )
        ) {
            throw new InvalidArgumentException('Missing required keys in data');
        }

        $product = $this->productRepository->findOrCreateByDataId($data['data_id']);
        $images = $this->syncImages($data, $product);

        foreach ($images->toArray() as $image) {
            ProcessImageDescriptionJob::dispatch($image['name'], $data['prompt']);
        }
    }

    /**
     * Synchronize images for a product
     */
    private function syncImages(array $data, Product $product): Collection
    {
        return $this->imageService->syncImages($data['images'], $product);
    }
}
