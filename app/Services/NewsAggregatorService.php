<?php

namespace App\Services;

use App\Services\NewsServices\GuardianService;
use App\Services\NewsServices\NewsAPIService;
use App\Services\NewsServices\NYTService;

class NewsAggregatorService
{
    protected $services;

    public function __construct(NYTService $nytService, GuardianService $guardianService, NewsAPIService $newsAPIService)
    {
        $this->services = [$nytService, $guardianService, $newsAPIService];
    }

    public function fetchArticles(): array
    {
        $allArticles = [];

        foreach ($this->services as $service) {
            $articles = $service->fetch();
            $formattedArticles = $service->format($articles);
            $allArticles = array_merge($allArticles, $formattedArticles);
        }

        return $allArticles;
    }
}
