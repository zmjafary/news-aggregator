<?php

namespace App\Services;

abstract class BaseNewsService
{
    protected $baseUrl;
    protected $apiKey;
    protected $source;
    
    abstract public function fetch(): array;

    abstract public function format(array $articles): array;
}
