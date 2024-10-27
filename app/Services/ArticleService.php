<?php

namespace App\Services;

use App\Repositories\ArticleRepository;
use App\Services\NewsAggregatorService;


class ArticleService {

    protected $newsAggregatorService;
    protected $articleRepository;

    public function __construct(NewsAggregatorService $newsAggregatorService, ArticleRepository $articleRepository)
    {
        $this->newsAggregatorService = $newsAggregatorService;
        $this->articleRepository = $articleRepository;
    }

    public function fetchAndStoreArticles(){
        $articles = $this->newsAggregatorService->fetchArticles();

        foreach($articles as $article){
            $this->articleRepository->create($article);
        }
    }
}