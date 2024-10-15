<?php

namespace App\Repositories;

use App\Models\Product;

/**
 * ProductRepository class
 */
class ProductRepository
{
    /**
     * Find a product by product ID
     *
     * @param int $id
     * @return Product|null
     */
    public function find(int $id): ?Product
    {
        return Product::findOrFail($id);
    }

    /**
     * Find or create a product by product ID
     *
     * @param int $dataId
     * @param string $prompt
     * @return Product
     */
    public function findOrCreateByDataId(int $dataId, string $prompt): Product
    {
        return Product::updateOrCreate(
            ['data_id' => $dataId],
            [
                'prompt' => $prompt,
            ]
        );
    }

    /**
     * Retrieves images with descriptions for a given product ID.
     *
     * @param int $productId The ID of the product to retrieve images for.
     * @return array An array of images with descriptions.
     */
    public function getImagesWithDescription(int $productId): array
    {
        $images = $this->find($productId)->images()->get();
        $imagesWithDescription = [];

        foreach ($images as $image) {
            $imagesWithDescription[basename($image->getAttribute('name'))]
                = $image->getAttribute('description');
        }

        return $imagesWithDescription;
    }
}
