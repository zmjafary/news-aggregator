<?php

namespace App\Services\NewsServices;

use App\Services\BaseNewsService;
use Illuminate\Support\Facades\Http;

class GuardianService extends BaseNewsService
{
    protected $baseUrl;
    protected $apiKey;
    protected $source = "The Guardian";

    public function __construct()
    {
        $this->baseUrl = 'https://content.guardianapis.com/search';
        $this->apiKey = config('services.guardian.key');
    }

    public function fetch(): array
    {
        $response = Http::get($this->baseUrl, [
            'order-by' => 'newest',
            'show-tags' => 'contributor',
            'api-key' => $this->apiKey
        ]);

        return $response->json()['response']['results'] ?? [];
    }

    public function format(array $articles): array
    {
        return collect($articles)->map(function ($article) {
            return [
                'title' => $article['webTitle'] ?? '',
                'description' => '', // Guardian API does not provide a description directly
                'category' => $article['sectionName'] ??'',
                'authors' =>  $this->getAuthors($article),
                'url' => $article['webUrl'] ?? '',
                'image' => '', // No image URL in Guardian API response
                'published_at' => (new \DateTime($article['webPublicationDate']))->format('Y-m-d H:i:s') ?? '',
                'source' => $this->source,
            ];
        })->all();
    }


    public function getAuthors($article) {
        $authors = [];
    
        foreach ($article['tags'] as $tag) {
            if ($tag['type'] === 'contributor') {
                $authors[] = $tag['firstName'] . ' ' . $tag['lastName'];
            }
        }
    
        return implode(', ', $authors);
    }
}
