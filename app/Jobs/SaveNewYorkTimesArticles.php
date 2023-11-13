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

class SaveNewYorkTimesArticles //implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $articles)
    {
        $this->onQueue(config('datasource.new_york_times.queue'));
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $articlesData = [];
        foreach ($this->articles as $article) {
            if (isset($article['subsection_name'])) {
                $source = Source::firstOrCreate([
                    'name' => $article['section_name'],
                    'title' => $article['subsection_name'],
                ]);

                $articlesData[] = [
                    'source_id' => $source->id,
                    'load_resource' => ArticleResource::NEW_YORK_TIMES,
                    'type' => $article['type_of_material'],
                    'author' => $article['byline']['original'] ?? '',
                    'published_at' => Carbon::parse($article['pub_date']),
                    'title' => $article['abstract'],
                    'url' => $article['web_url'],
                    'description' => $article['snippet'],
                    'content' => $article['lead_paragraph'],
                    'url_to_image' => isset($article['multimedia'][0]) ? $article['multimedia'][0]['url'] : '',
                ];
            }

            Article::insert($articlesData);
        }
    }
}
