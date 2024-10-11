<?php

namespace App\Http\Controllers;

use App\Constants\OtDushiAiProcessTypes;
use App\Exceptions\InvalidProcessTypeException;
use App\Http\Requests\GetAiSpreadsRequest;
use App\Services\OtDushiAi\Processors\OtDushiAiProcessor;
use Exception;
use Illuminate\Http\JsonResponse;

/**
 * OtDushiApiController
 *
 * Controller for handling OpenAiService requests.
 */
class OtDushiApiController extends Controller
{
    /**
     * Describe an image using OpenAiService.
     *
     * This method sends an image to OpenAiService for analysis and returns the response from OpenAiService.
     *
     * @param GetAiSpreadsRequest $request
     *
     * @return JsonResponse
     */
    public function getAiSpreads(GetAiSpreadsRequest $request): JsonResponse
    {
        $imagesUrl = $request->input('images');
        $prompt = $request->input('prompt');
        $processor = new OtDushiAiProcessor();

        try {
            $processor->process(
                [$imagesUrl, $prompt],
                OtDushiAiProcessTypes::GET_AI_IMAGES_DESCRIPTION
            );

            return $this->respondJsONWithSuccess();
        } catch (InvalidProcessTypeException $e) {
            return $this->respondJsonWithError(
                $e->getMessage(),
                400
            );
        } catch (Exception $e) {
            return $this->respondJsonWithError(
                $e->getMessage(),
                500
            );
        }
    }
}