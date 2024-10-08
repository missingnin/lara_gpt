<?php

namespace App\Providers;

use App\Services\OpenAiService;
use App\Services\OpenAiServiceInterface;
use App\Services\OtDushiAiService;
use App\Services\OtDushiAiServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(OpenAiServiceInterface::class, OpenAiService::class);
        $this->app->bind(OtDushiAiServiceInterface::class, OtDushiAiService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
