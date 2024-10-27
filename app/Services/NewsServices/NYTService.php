<?php

namespace App\Services\NewsServices;

use App\Services\BaseNewsService;
use Illuminate\Support\Facades\Http;

class NYTService extends BaseNewsService
{
    protected $baseUrl;
    protected $apiKey;
    protected $source = "New York Times";

    public function __construct()
    {
        $this->baseUrl = 'https://api.nytimes.com/svc/news/v3/content/all/all.json';
        $this->apiKey = config('services.nyt.key');
    }

    public function fetch(): array
    {
        $response = Http::get($this->baseUrl, [
            'api-key' => $this->apiKey
        ]);

        return $response->json()['results'] ?? [];
    }

    public function format(array $articles): array
    {
        return collect($articles)->map(function ($article) {
            return [
                'title' => $article['title'] ?? '',
                'description' => $article['abstract'] ?? '',
                'category' => $article['subsection'] ?? $article['section'],
                'authors' => $this->getAuthors($article),
                'url' => $article['url'] ?? '',
                'image' => $article['multimedia'][0]['url'] ?? '',
                'published_at' => $article['published_date'] ?? '',
                'source' => $this->source,
            ];
        })->all();
    }

    public function getAuthors($article) {
        $authors = str_replace(['By ', 'and '], '', $article['byline']);
        $authorsArray = array_map('trim', explode(',', $authors));
        return implode(', ', $authorsArray);
    }
}
