<?php

namespace App\Services;

use App\Models\AppSetting;
use http\Env\Request;
use mysql_xdevapi\Exception;

class AppSettingsService
{
    public function getWidgetSettings()
    {
        try {
            return AppSetting::where('key', 'widget_settings')->firstOrFail();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateWidgetSettings(array $updatedWidgetData)
    {
        $contextsData = [];

        if (!empty($updatedWidgetData)) {
            if (isset($updatedWidgetData['context'])) {
                foreach ($updatedWidgetData['context'] as $context) {
                    $contextsData[] = $context;
                }
            }
        }

        try {
            $widgetSettings = AppSetting::where('key', 'widget_settings')
                ->firstOrFail();
            if ($widgetSettings) {
                $widgetSettings->value = [
                    'instruction' => $updatedWidgetData['instruction'],
                    'model' => $updatedWidgetData['model'],
                    'maxTokens' => $updatedWidgetData['maxTokens'],
                    'context' => $contextsData
                ];
                $widgetSettings->save();
            }
        } catch (\Exception $e) {
            $widgetSettings = null;
        }

        return $widgetSettings;
    }
}
