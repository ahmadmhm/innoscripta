<?php

namespace App\Jobs;

use App\Enums\ArticleResource;
use App\Models\Article;
use App\Models\Source;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveGuardianNewsArticles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $articles)
    {
        $this->onQueue(config('datasource.guardian.queue'));
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $articlesData = [];
        foreach ($this->articles as $article) {
            $source = Source::firstOrCreate([
                'name' => $article['sectionId'],
                'title' => $article['sectionName'],
            ]);

            $articlesData[] = [
                'source_id' => $source->id,
                'load_resource' => ArticleResource::GUARDIAN,
                'type' => $article['type'],
                'author' => $article['fields']['byline'] ?? '',
                'published_at' => Carbon::parse($article['webPublicationDate']),
                'title' => $article['webTitle'],
                'url' => $article['webUrl'],
                'description' => $article['fields']['main'] ?? '',
                'content' => $article['fields']['bodyText'] ?? '',
                'url_to_image' => $article['fields']['thumbnail'] ?? '',
            ];

            Article::insert($articlesData);
        }
    }
}
