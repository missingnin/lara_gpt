<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface for product services.
 */
interface ProductServiceInterface
{
    /**
     * Synchronizes images with a product.
     *
     * @param string $imagesPrompt The prompt for the images
     * @param array $images        An array of images to synchronize
     * @param Product $product     The product to synchronize images with
     *
     * @return Collection A collection of synchronized images
     */
    public function syncImages(string $imagesPrompt, array $images, Product $product): Collection;
}