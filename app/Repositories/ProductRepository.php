<?php

namespace App\Repositories;

use App\Models\Product;

/**
 * ProductRepository class
 */
class ProductRepository
{
    /**
     * Find or create a product by product ID
     *
     * @param int $dataId
     * @return Product
     */
    public function findOrCreateByDataId(int $dataId): Product
    {
        $product = Product::where('data_id', $dataId)->first();

        if (!$product) {
            $product = Product::create(
                ['data_id' => $dataId,]);
        }

        return $product;
    }
}
