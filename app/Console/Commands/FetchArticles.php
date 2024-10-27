<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ArticleService;

class FetchArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches articles and stored them in the database!';
    
    /**
     * Execute the console command.
     */
    public function handle(ArticleService $articleService)
    {
        $articleService->fetchAndStoreArticles();
        $this->info('Articles have been fetched and stored successfully!');
    }
}
