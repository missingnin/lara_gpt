<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

/**
 * Interface for image services
 */
interface ImageServiceInterface
{
    /**
     * Synchronizes images with a product
     *
     * @param array $images An array of images to synchronize
     * @param Product $product
     * @return Collection
     */
    public function syncImages(array $images, Product $product): Collection;
}
