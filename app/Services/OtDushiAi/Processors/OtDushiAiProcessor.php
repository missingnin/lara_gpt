<?php

namespace App\Services\OtDushiAi\Processors;

use App\Constants\OtDushiAiProcessTypes;
use App\Exceptions\InvalidProcessTypeException;
use App\Jobs\ProcessImageDescriptionJob;
use App\Repositories\ProductRepository;
use App\Services\ImageServiceInterface;
use App\Services\ProductServiceInterface;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;

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
     * @var ProductServiceInterface
     */
    private ProductServiceInterface $productService;

    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Constructor
     *
     * @param ImageServiceInterface $imageService
     * @param ProductServiceInterface $productService
     * @param ProductRepository $productRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        ImageServiceInterface $imageService,
        ProductServiceInterface $productService,
        ProductRepository $productRepository,
        LoggerInterface $logger
    ) {
        $this->imageService = $imageService;
        $this->productService = $productService;
        $this->productRepository = $productRepository;
        $this->logger = $logger;
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
        $this->logger->info('Starting processing data with process type ' . $processType);

        try {
            match ($processType) {
                OtDushiAiProcessTypes::GET_AI_IMAGES_DESCRIPTION => $this->processImageDescription($data),
                OtDushiAiProcessTypes::GET_AI_SPREADS_GROUPS => $this->processSpreadsDescription($data),
                default => throw new InvalidProcessTypeException($processType),
            };
        } catch (InvalidProcessTypeException $e) {
            $this->logger->error('Invalid process type: ' . $e->getMessage());
            throw $e;
        }

        $this->logger->info('Finished processing data with process type ' . $processType);
    }

    /**
     * Process image description
     *
     * @param array $data
     * @throws InvalidArgumentException
     */
    private function processImageDescription(array $data): void
    {
        $this->logger->info('Starting processing image description');

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
            $images = $this->productService->syncImages($data['images_prompt'], $data['images'], $product);

            foreach ($images->toArray() as $image) {
                $this->logger->info('Dispatching ProcessImageDescriptionJob for image ' . $image['name']);
                ProcessImageDescriptionJob::dispatch($image['name'], $data['images_prompt']);
            }
        } catch (InvalidArgumentException $e) {
            $this->logger->error('Invalid data: ' . $e->getMessage());
            throw $e;
        }

        $this->logger->info('Finished processing image description');
    }

    /**
     * Process spreads description
     *
     * @param array $data
     * @throws InvalidArgumentException
     */
    private function processSpreadsDescription(array $data): void
    {
        if (!isset($data['product_id'])) {
            throw new InvalidArgumentException('Missing required product id');
        }

        $this->logger->info('Retrieving images with descriptions for product id ' . $data['product_id']);
        $imagesWithDescription = $this->productRepository->getImagesWithDescription($data['product_id']);

        $this->logger->info('Retrieved images with descriptions:', $imagesWithDescription);
    }
}
