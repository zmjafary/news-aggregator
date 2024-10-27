<?php

namespace App\Repositories;
use App\Models\Article;
use Illuminate\Support\Facades\Cache;

class ArticleRepository
{
    public function index($request)
    {
        $cacheKey = 'articles_' . md5(json_encode($request->all()));

        return Cache::remember($cacheKey, 3600, function () use ($request) {
            $query = Article::query();

            if ($request->filled('keyword')) {
                $keyword = '%' . $request->keyword . '%';
                $query->where(function ($query) use ($keyword) {
                    $query->where('title', 'LIKE', $keyword)
                        ->orWhere('description', 'LIKE', $keyword);
                });
            }

            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }
            if ($request->filled('source')) {
                $query->where('source', $request->source);
            }
            if ($request->filled('date')) {
                $query->whereDate('published_at', $request->date);
            }

            return $query->paginate(10);
        });
    }


    public function show($id)
    {
        return Article::findOrFail($id);
    }

    public function create(array $data)
    {
        return Article::updateOrCreate(
            ['url' => $data['url']],
            $data
        );
    }

    public function personalizedFeed($preferences)
    {
        $cacheKey = 'personalized_feed_' . md5(json_encode($preferences));

        return Cache::remember($cacheKey, 3600, function () use ($preferences) {
            $query = Article::query();

            foreach ($preferences as $preference) {
                if (!empty($preference->value)) {
                    $query->where(function ($subQuery) use ($preference) {
                        foreach ($preference->value as $value) {
                            $subQuery->orWhere($preference->filter, 'like', '%' . $value . '%');
                        }
                    });
                }
            }

            return $query->paginate(10);
        });
    }

}