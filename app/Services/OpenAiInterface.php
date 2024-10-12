<?php

namespace App\Services;

use App\ValueObject\ImageDescriptionResult;
use Exception;

/**
 * Interface for interacting with OtDushi Ai Service.
 * This interface defines the methods that must be implemented by a class working with OtDushiAiService.
 *
 * @package App\Services
 */
interface OpenAiInterface
{
    /**
     * Gets the image description from OtDushi Ai Service.
     *
     * This method sends a request to OtDushi Ai Service to get the image description
     * and returns the response.
     *
     * @param string $imageUrl The URL of the image.
     * @param string $prompt The prompt for the image description.
     *
     * @return ImageDescriptionResult The image description.
     *
     * @throws Exception If the request to OtDushi Ai Service fails.
     */
    public function getImageDescription(string $imageUrl, string $prompt): ImageDescriptionResult;
}
