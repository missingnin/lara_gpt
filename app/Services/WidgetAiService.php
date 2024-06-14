<?php

namespace App\Services;

use App\Models\AppSetting;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Chat\CreateResponse;

class WidgetAiService
{
    public function process($data)
    {
        if (!empty($data)) {
            if (isset($data['event_type'])) {
                if ($data['event_type']) {
                    $result = $this->makeAnswer($data['chat_history'], $data['event_type']);
                }
            }
        }

        return $result['choices'][0]['message']['content'];
    }

    public function makeAnswer($chatHistory, $mode): CreateResponse
    {
        $messages = [];
        $widgetSettings = json_decode(AppSetting::where('key', 'widget_settings')
            ->firstOrFail()
            ->value, true);

        $messages[] = [
            'role' => 'system',
            'content' => $widgetSettings['instruction'],
        ];

        foreach ($chatHistory as $message) {
            $role = $message['is_client'] ? 'user' : 'assistant';
            $content = $message['message'] . $widgetSettings['context'][$mode]['value'];

            $messages[] = [
                'role' => $role,
                'content' => $content,
            ];
        }

        return OpenAI::chat()->create([
            'model' => $widgetSettings['model'],
            'messages' => $messages,
            'max_tokens' => (int)$widgetSettings['maxTokens'],
        ]);
    }
}
