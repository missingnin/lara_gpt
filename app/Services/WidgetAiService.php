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
                    $result = $this->makeAnswer($data['chat_history'], $data['event_type'], $data['model']);
                }
            }
        }

        return $result['choices'][0]['message']['content'];
    }

    public function makeAnswer($chatHistory, $mode, $model): CreateResponse
    {
        $messages = [];
        $widgetSettings = json_decode(AppSetting::where('key', 'widget_settings')
            ->firstOrFail()
            ->value, true);

        foreach ($chatHistory as $message) {
            $role = $message['is_client']? 'user' : 'assistant';
            $content = $message['message']. $widgetSettings['context'][$mode - 1]['value'];

            $messages[] = [
                'role' => $role,
                'content' => $content,
            ];
        }

        $messages[] = [
            'role' => 'system',
            'content' => $widgetSettings['instruction'],
        ];

        $jsonMessages = json_encode($messages, JSON_PRETTY_PRINT);

        file_put_contents(base_path('1.json'), $jsonMessages);

        return OpenAI::chat()->create([
            'model' => $model,
            'messages' => $messages,
            'max_tokens' => (int)$widgetSettings['maxTokens'],
        ]);
    }}
