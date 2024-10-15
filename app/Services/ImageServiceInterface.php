<?php

namespace App\Services;

/**
 * Interface for image services
 */
interface ImageServiceInterface
{
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
