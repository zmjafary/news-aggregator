<?php

namespace App\Services\NewsServices;

use App\Services\BaseNewsService;
use Illuminate\Support\Facades\Http;

class NewsAPIService extends BaseNewsService
{
    protected $baseUrl;
    protected $apiKey;
    protected $source = "News API";

    public function __construct()
    {
        $this->baseUrl = 'https://newsapi.org/v2/top-headlines';
        $this->apiKey = config('services.newsapi.key');
    }

    public function fetch(): array
    {
        $response = Http::get($this->baseUrl, [
            'category' => 'general',
            'apiKey' => $this->apiKey
        ]);

        return $response->json()['articles'] ?? [];
    }

    public function format(array $articles): array
    {
        return collect($articles)->map(function ($article) {
            return [
                'title' => $article['title'] ?? '',
                'description' => $article['description'] ?? '',
                'category' => $article['source']['name'] ?? '',
                'authors' => $this->getAuthors($article) ?? '',
                'url' => $article['url'] ?? '',
                'image' => $article['urlToImage'] ?? '',
                'published_at' => (new \DateTime($article['publishedAt']))->format('Y-m-d H:i:s') ?? '',
                'source' => $this->source,
            ];
        })->all();
    }

    public function getAuthors($article) {
        $authors = $article['author'];
        $authorsArray = array_map(function($author) {
            $cleanedAuthor = trim(explode('|', $author)[0]);
            return ucwords(strtolower($cleanedAuthor));
        }, explode(',', $authors));

        return implode(', ', $authorsArray);
    }
}
