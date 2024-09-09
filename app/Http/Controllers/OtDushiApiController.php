<?php

namespace App\Http\Controllers;

use App\Services\OtDushiAiServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OtDushiApiController extends Controller
{
    /**
     * @var OtDushiAiServiceInterface
     */
    private OtDushiAiServiceInterface $otDushiAi;

    /**
     * Constructor.
     *
     * @param OtDushiAiServiceInterface $otDushiAi
     */
    public function __construct(OtDushiAiServiceInterface $otDushiAi)
    {
        $this->otDushiAi = $otDushiAi;
    }

    /**
     * Describe an image using OpenAiService.
     *
     * This method sends an image to OpenAiService for analysis and returns the response from OpenAiService.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getAiSpreads(Request $request): JsonResponse
    {
        $imagesUrl = $request->input('images');
        $prompt = $request->input('prompt');

        if (empty($request->input('images')) || empty($request->input('prompt'))) {
            return response()->json(
                ['error' => 'Image URL and prompt are required'],
                400
            );
        }

        try {
            $response = $this->otDushiAi->getSpreadsFromOpenAi([$imagesUrl], $prompt);

            if ($response->getErrorMessage() !== null) {
                return response()->json(['error' => $response->getErrorMessage()], $response->getErrorCode() ?? 400);
            }

            return response()->json($response->getSpreads());
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
