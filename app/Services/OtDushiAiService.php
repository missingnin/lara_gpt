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
     * @param OpenAI $openAi
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
     * @param array $images The array of images to send to OpenAiService.
     * @param string $prompt The text for Prompt
     *
     * @return OtDushiAiSpreadsResult Prepared Spreads Result;
     *
     * @throws Exception If the request to OpenAiService fails.
     */
    public function getSpreadsFromOpenAi(array $images, string $prompt): OtDushiAiSpreadsResult
    {
        $model = '';
        $messages = $this->prepareSpreadsRequestMessage($images);
        $response = $this
            ->openAiClient
            ->createCommonRequest($model, $messages, 8000);

        return new OtDushiAiSpreadsResult($response->json());
    }

    /**
     * Prepares an array of images for sending to OpenAiService.
     *
     * This method prepares an array of images for sending
     * to OpenAiService by converting them to a format that can be sent to OpenAiService.
     *
     * @param array $images The array of images to prepare.
     *
     * @return array The prepared array of images.
     */
    private function prepareImagesForRequest(array $images): array
    {
        $preparedImages = [];
        foreach ($images as $image) {
            $preparedImages[] = [
                'image' => base64_encode(file_get_contents($image)),
            ];
        }
        return $preparedImages;
    }

    /**
     * Prepares an array of images for sending to OpenAiService.
     *
     * This method prepares an array of images for sending
     * to OpenAiService by converting them to a format that can be sent to OpenAiService.
     *
     * @param array $images The array of images to prepare.
     *
     * @return array The prepared array of images.
     */
    private function prepareSpreadsRequestMessage(array $images): array
    {
        $preparedImages = [];
        foreach ($images as $image) {
            $preparedImages[] = [
                'image' => base64_encode(file_get_contents($image)),
            ];
        }
        return $preparedImages;
    }
}
