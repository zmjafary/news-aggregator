<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\NewsAggregatorService;
use App\Services\NewsServices\NYTService;
use App\Services\NewsServices\GuardianService;
use App\Services\NewsServices\NewsAPIService;

class NewsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(NewsAggregatorService::class, function ($app) {
            return new NewsAggregatorService(
                new NYTService(),
                new GuardianService(),
                new NewsAPIService(),
            );
        });
    }
}
