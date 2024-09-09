<?php

namespace App\Services;

use App\ValueObject\OtDushiAiSpreadsResult;
use Exception;

/**
 * Interface for interacting with OtDushi Ai Service.
 * This interface defines the methods that must be implemented by a class working with OtDushiAiService.
 *
 * @package App\Services
 */
interface OtDushiAiServiceInterface
{
    /**
     * Sends an array of images to OpenAiService for processing.
     *
     * This method sends an array of images to OpenAiService for processing and returns the response from OpenAiService.
     *
     * @param array $imagesUrl The array of images to send to OpenAiService.
     * @param string $prompt The text for Prompt
     * @return OtDushiAiSpreadsResult Spreads Result.
     *
     * @throws Exception If the request to OpenAiService fails.
     */
    public function getSpreadsFromOpenAi(array $imagesUrl, string $prompt): OtDushiAiSpreadsResult;
}
