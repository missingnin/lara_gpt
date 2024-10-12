<?php

namespace App\Services\Clients;

use App\Services\OpenAiInterface;
use App\ValueObject\ImageDescriptionResult;
use App\ValueObject\OtDushiAiSpreadsResult;
use Exception;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Chat\CreateResponse;

/**
 * Class for interacting with OpenAI.
 * This class provides methods for working with OpenAI.
 *
 * @package App/Services
 */
class OpenAiClient implements OpenAiInterface
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
    protected function createCommonRequest(string $model, array $messages, int $maxTokens): CreateResponse
    {
        return OpenAI::chat()->create([
            'model' => $model,
            'messages' => $messages,
            'max_tokens' => $maxTokens,
        ]);
    }

    /**
     * Gets the image description from OpenAI.
     *
     * This method sends a request to OpenAI to get the image description
     * and returns the response as an OtDushiAiSpreadsResult object.
     *
     * @param string $imageUrl The URL of the image.
     * @param string $prompt The prompt for the image description.
     *
     * @return ImageDescriptionResult The image description result.
     *
     * @throws Exception If the request to OpenAI fails.
     */
    public function getImageDescription(string $imageUrl, string $prompt): ImageDescriptionResult
    {
        $model = 'gpt-4o-mini';
        $messages = [
            [
                'role' => 'user',
                'content' => [
                    [
                        "type" => "text",
                        "text" => $prompt,
                    ],
                    [
                        "type" => "image_url",
                        "image_url" => [
                            "url" => $imageUrl,
                        ],
                    ],
                ],
            ],
        ];

        $response = $this->createCommonRequest($model, $messages, 300);

        $data = [
            'content' => $response->choices[0]->message->content,
            'choices' => $response->choices,
        ];

        return new ImageDescriptionResult($data);
    }
}
