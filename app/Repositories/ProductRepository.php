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
        return Product::findOrFal($id);
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
}
