<?php

namespace App\Services;

use App\ValueObject\OtDushiAiSpreadsResult;
use Exception;

/**
 * Class for interacting with OtDushi Ai Service.
 *
 * This class implements the OtDushiAiServiceInterface
 * and provides methods for working with OtDushi Ai Service.
 *
 * @package App\Services
 */
class OtDushiAiService implements OtDushiAiServiceInterface
{
    /**
     * @var OpenAiServiceInterface
     */
    private OpenAiServiceInterface $openAiClient;

    /**
     * Constructor.
     *
     * @param OpenAiServiceInterface $openAiClient
     */
    public function __construct(OpenAiServiceInterface $openAiClient)
    {
        $this->openAiClient = $openAiClient;
    }

    /**
     * Sends an array of images to OpenAiService for processing.
     *
     * This method sends an array of images to OpenAiService
     * for processing and returns the response from OpenAiService.
     *
     * @param array $imagesUrl The array of images to send to OpenAiService.
     * @param string $prompt The text for Prompt
     *
     * @return OtDushiAiSpreadsResult Prepared Spreads Result;
     *
     * @throws Exception If the request to OpenAiService fails.
     */
    public function getSpreadsFromOpenAi(array $imagesUrl, string $prompt): OtDushiAiSpreadsResult
    {
        $model = '';
        $messages = $this->prepareSpreadsMessage($imagesUrl, $prompt);
        $response = $this
            ->openAiClient
            ->createCommonRequest($model, $messages, 8000);

        return new OtDushiAiSpreadsResult($response->toArray());
    }

    /**
     * Prepares the content for sending to OpenAiService.
     *
     * This method prepares the content for sending to OpenAiService by converting it to a format that can be sent to OpenAiService.
     *
     * @param array $imagesUrl The array of images to prepare.
     * @param string $prompt The text for Prompt
     *
     * @return array The prepared content.
     */
    private function prepareSpreadsMessage(array $imagesUrl, string $prompt): array
    {
        $messages = [
            [
                'role' => 'system',
                'content' => [
                    [
                        "type" => "text",
                        "text" => $prompt,
                    ],
                ],
            ],
        ];

        foreach ($imagesUrl as $image) {
            $messages[] = [
                'role' => 'user',
                'content' => [
                    [
                        "type" => "image_url",
                        "image_url" => [
                            "url" => $image,
                        ],
                    ],
                ],
            ];
        }

        return [
            'messages' => $messages,
        ];
    }
}
