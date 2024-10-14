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
     * @param string $imagesPrompt
     * @param array $images An array of images to synchronize
     * @param Product $product
     *
     * @return Collection
     */
    public function syncImages(string $imagesPrompt, array $images, Product $product): Collection;

    /**
     * Check if an image is accessible by its URL.
     *
     * Sends a HEAD request to the image URL and checks if the response status code is 200
     *
     * @param string $imageUrl The image URL to check
     *
     * @return bool True if the image is accessible, false otherwise
     */
    public function isImageUrlAccessible(string $imageUrl): bool;

    /**
     * Handles image description
     *
     * @param string $imageDescription
     * @param string $imageUrl
     *
     * @return void
     */
    public function handleImageDescription(string $imageDescription, string $imageUrl): void;
}
