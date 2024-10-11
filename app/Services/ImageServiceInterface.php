<?php

namespace App\Services;

/**
 * Interface for image services
 */
interface ImageServiceInterface
{
    /**
     * Synchronizes images with a product
     *
     * @param array $images An array of images to synchronize
     * @param int $productId The ID of the product to synchronize images with
     * @return void
     */
    public function syncImages(array $images, int $productId): void;
}
