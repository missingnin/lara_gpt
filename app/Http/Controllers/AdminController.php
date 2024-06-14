<?php

namespace App\Http\Controllers;

use App\Services\AppSettingsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected AppSettingsService $settingsService;

    public function __construct()
    {
        $this->settingsService = new AppSettingsService();
    }

    public function getWidgetSettings()
    {
        return $this->settingsService->getWidgetSettings();
    }

    public function updateWidgetSettings(Request $request): \Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
    {
        $this->settingsService->updateWidgetSettings($request->all());
        return redirect('/dashboard');
    }
}
