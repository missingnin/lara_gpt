<?php

namespace App\Services\OtDushiAi\Processors;

use App\Constants\OtDushiAiProcessTypes;
use App\Exceptions\InvalidProcessTypeException;
use App\Jobs\ProcessImageDescriptionJob;
use App\Repositories\ProductRepository;
use App\Services\ImageServiceInterface;
use InvalidArgumentException;
use Illuminate\Support\Facades\Log;

/**
 * Class for OtDushi AI processors
 */
class OtDushiAiProcessor
{
    /**
     * @var ImageServiceInterface
     */
    private ImageServiceInterface $imageService;

    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    /**
     * Constructor
     *
     * @param ImageServiceInterface $imageService
     * @param ProductRepository $productRepository
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
     * @param array $data
     * @param int $processType
     * @throws InvalidProcessTypeException
     */
    public function process(array $data, int $processType): void
    {
        Log::info('Starting processing data with process type ' . $processType);

        try {
            match ($processType) {
                OtDushiAiProcessTypes::GET_AI_IMAGES_DESCRIPTION => $this->processImageDescription($data),
                default => throw new InvalidProcessTypeException($processType),
            };
        } catch (InvalidProcessTypeException $e) {
            Log::error('Invalid process type: ' . $e->getMessage());
            throw $e;
        }

        Log::info(
            'Finished processing data with process type '
            . $processType
        );
    }

    /**
     * Process image description
     *
     * @param array $data
     * @throws InvalidArgumentException
     */
    private function processImageDescription(array $data): void
    {
        Log::info('Starting processing image description');

        try {
            if (
                !isset(
                    $data['images'],
                    $data['images_prompt'],
                    $data['spreads_prompt'],
                    $data['data_id']
                )
            ) {
                throw new InvalidArgumentException('Missing required keys in data');
            }

            $product = $this->productRepository->findOrCreateByDataId($data['data_id'], $data['spreads_prompt']);
            $images = $this->imageService->syncImages($data['images_prompt'], $data['images'], $product);

            foreach ($images->toArray() as $image) {
                Log::info('Dispatching ProcessImageDescriptionJob for image ' . $image['name']);
                ProcessImageDescriptionJob::dispatch($image['name'], $data['images_prompt']);
            }
        } catch (InvalidArgumentException $e) {
            Log::error('Invalid data: ' . $e->getMessage());
            throw $e;
        }

        Log::info('Finished processing image description');
    }
}
