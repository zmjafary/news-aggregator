<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public const FILTERS = ['authors', 'category', 'source'];

    protected $fillable = [
        'title',
        'description',
        'url',
        'category',
        'authors',
        'image',
        'published_at',
        'source',
    ];

    public function getAuthors(){
        return explode(',', $this->authors);
    }
}
