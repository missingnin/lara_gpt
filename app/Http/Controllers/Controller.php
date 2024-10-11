<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

/**
 * Controller
 *
 * Base controller class for the application.
 *
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Respond with an error message and a specified status code.
     *
     * @param string $message The error message to return.
     * @param int $statusCode The HTTP status code to return.
     *
     * @return JsonResponse
     */
    protected function respondJsonWithError(string $message, int $statusCode): JsonResponse
    {
        return response()->json(['error' => $message], $statusCode);
    }

    /**
     * Respond with a success indicator and optional data.
     *
     * @param array $data Optional data to return along with the success indicator.
     *
     * @return JsonResponse
     */
    protected function respondJsonWithSuccess(array $data = []): JsonResponse
    {
        return response()->json(array_merge(['success' => true], $data));
    }
}
