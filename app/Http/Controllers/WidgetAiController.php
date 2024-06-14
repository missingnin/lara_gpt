<?php

namespace App\Http\Controllers;

use App\Services\WidgetAiService;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class WidgetAiController extends Controller
{
    private WidgetAiService $aiService;

    public function __construct(WidgetAiService $aiService) {
        $this->aiService = $aiService;
    }

    public function process(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        return response()->json($this->aiService->process($data));
    }
}
