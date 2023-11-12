<?php

namespace App\Models;

use App\Enums\ArticleResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_id',
        'load_resource',
        'author',
        'type',
        'title',
        'description',
        'content',
        'url',
        'url_to_image',
        'published_at',
    ];

    protected $casts = [
        'load_resource' => ArticleResource::class,
    ];

    public function source()
    {
        return $this->belongsTo(Source::class);
    }
}
