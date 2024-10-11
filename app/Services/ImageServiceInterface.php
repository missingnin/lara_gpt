<?php

namespace App\Services;

/**
 * Interface for image services
 */
interface ImageServiceInterface
{
    /**
     * Get the image description
     *
     * @param array $images
     * @param int $productID
     * @return void
     */
    public function processImagesDescription(array $images, int $productID): void;
}