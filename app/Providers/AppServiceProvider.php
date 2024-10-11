<?php

namespace App\Providers;

use App\Repositories\ImageRepository;
use App\Repositories\ProductRepository;
use App\Services\Clients\OpenAiClient;
use App\Services\ImageService;
use App\Services\ImageServiceInterface;
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
        /** Services */
        $this->app->bind(OpenAiServiceInterface::class, OpenAiClient::class);
        $this->app->bind(OtDushiAiServiceInterface::class, OtDushiAiService::class);
        $this->app->bind(ImageServiceInterface::class, ImageService::class);

        /** Repositories */
        $this->app->bind(ImageRepository::class, ImageRepository::class);
        $this->app->bind(ProductRepository::class, ProductRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //TODO
    }
}
