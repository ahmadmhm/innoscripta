<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    public $fillable = [
        'source_id',
        'resource',
        'author',
        'type',
        'title',
        'description',
        'content',
        'url',
        'url_to_image',
        'published_at',
    ];

    public function source()
    {
        return $this->belongsTo(Source::class);
    }
}
