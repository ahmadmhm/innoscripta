<?php

namespace App\Jobs;

use App\Models\Article;
use App\Models\Source;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveNewsApiArticles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $articles)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $articlesData = [];
        foreach ($this->articles as $article) {
            $source = Source::firstOrCreate([
                'name' => $article?->source->id,
                'title' => $article?->source->name,
            ]);
            $articlesData[] = [
                'resource' => 'newsapi',
                'source_id' => $source->id,
                'author' => $article->author,
                'title' => $article->title,
                'description' => $article->description,
                'content' => $article->content,
                'url' => $article->url,
                'url_to_image' => $article->urlToImage,
                'published_at' => Carbon::parse($article->publishedAt),
            ];

            Article::insert($articlesData);
        }
    }
}
