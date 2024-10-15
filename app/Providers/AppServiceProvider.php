<?php

namespace App\Providers;

use App\Services\Clients\OpenAiClient;
use App\Services\ImageService;
use App\Services\ImageServiceInterface;
use App\Services\OpenAiInterface;
use App\Services\ProductService;
use App\Services\ProductServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        /** Services */
        $this->app->bind(OpenAiInterface::class, OpenAiClient::class);
        $this->app->bind(ImageServiceInterface::class, ImageService::class);
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
