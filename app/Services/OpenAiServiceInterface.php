<?php

namespace App\Services;

use Exception;
use OpenAI\Responses\Chat\CreateResponse;

/**
 * Interface for interacting with OpenAI.
 * This interface defines the methods that must be implemented by a class working with OpenAI.
 *
 * @package App\Services
 */
interface OpenAiServiceInterface
{
    /**
     * Creates a request to OpenAI with the specified parameters.
     *
     * This method creates a request to OpenAI with the specified parameters and returns the response from OpenAI.
     *
     * @param string $model The OpenAI model to use for processing the request.
     * @param array $messages The array of messages to send to OpenAI.
     * @param int $maxTokens The maximum number of tokens that can be in the response from OpenAI.
     *
     * @return CreateResponse The response from OpenAI as a CreateResponse object.
     *
     * @throws Exception If the request to OpenAI fails.
     */
    public function createCommonRequest(string $model, array $messages, int $maxTokens): CreateResponse;
}
