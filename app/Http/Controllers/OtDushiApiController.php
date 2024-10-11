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
     * @var OtDushiAiProcessor
     */
    private OtDushiAiProcessor $otDushiAiProcessor;

    /**
     * Constructor
     *
     * @param OtDushiAiProcessor $otDushiAiProcessor
     */
    public function __construct(OtDushiAiProcessor $otDushiAiProcessor)
    {
        $this->otDushiAiProcessor = $otDushiAiProcessor;
    }

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
        try {
            $this->otDushiAiProcessor->process(
                $request->all(),
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
