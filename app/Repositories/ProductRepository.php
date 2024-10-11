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
     * @param int $productId
     * @return Product
     */
    public function findOrCreateByProductId(int $productId): Product
    {
        $product = Product::where('id', $productId)->first();

        if (!$product) {
            $product = Product::create(['id' => $productId]);
        }

        return $product;
    }
}
