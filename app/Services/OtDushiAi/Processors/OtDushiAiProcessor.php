<?php

namespace App\Services\OtDushiAi\Processors;

use App\Constants\OtDushiAiProcessTypes;
use App\Exceptions\InvalidProcessTypeException;
use App\Repositories\ImageRepository;
use App\Repositories\ProductRepository;
use InvalidArgumentException;

/**
 * Class for OtDushi AI processors
 */
class OtDushiAiProcessor
{
    /**
     * Process data based on the process type
     *
     * @param mixed $data
     * @param string $processType
     *
     * @return mixed
     * @throws InvalidProcessTypeException
     */
    public function process($data, $processType): mixed
    {
        return match ($processType) {
            OtDushiAiProcessTypes::GET_AI_IMAGES_DESCRIPTION => $this->processImageDescription($data),
            OtDushiAiProcessTypes::GET_AI_SPREADS_GROUPS => $this->processSpreadsGroups($data),
            default => throw new InvalidProcessTypeException($processType),
        };
    }

    protected function processImageDescription(array $data): void
    {
        if (!isset($data['images'], $data['prompt'], $data['data_id'])) {
            throw new InvalidArgumentException("Missing required keys in data");
        }

        $productRepository = new ProductRepository();
        $imageRepository = new ImageRepository();

        $product = $productRepository
            ->findOrCreateByDataId($data['data_id']);
        $existingImages = $product
            ->images()
            ->get();

        $imageRepository->syncImages(
            $product->getAttribute('id'),
            $existingImages,
            $data['images']
        );
    }

    /**
     * Process spreads groups
     *
     * @param mixed $data
     * @return mixed
     */
    protected function processSpreadsGroups(array $data)
    {
        return;
    }
}
