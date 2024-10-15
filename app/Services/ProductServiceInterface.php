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

    /**
     * Get a collection of images of the product that need a description update.
     *
     * @param string $imagesPrompt
     * @param Collection $images
     * @return Collection A collection of images that need a description update
     */
    public function imagesForUpdatingDescription(string $imagesPrompt, Collection $images): Collection;
}
