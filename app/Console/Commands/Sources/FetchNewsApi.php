<?php

namespace App\Console\Commands\Sources;

use App\Jobs\SaveNewsApiArticles;
use Illuminate\Console\Command;
use jcobhams\NewsApi\NewsApi;

class FetchNewsApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'source:fetch-news-api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'With this command you`ll get the latest news available';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (! config('datasource.newsapi.api_key')) {
            $this->error('There is no api key available');

            return;
        }
        $newsApi = new NewsApi(config('datasource.newsapi.api_key'));
        $currentPage = 1;
        $hasMore = true;
        $pageSize = config('datasource.fetch.page_size');
        while ($hasMore) {
            try {
                $articles = $newsApi->getEverything(
                    q: 'Android',
                    sources : '', domains : '', exclude_domains : '',
                    from : now()->format('Y-m-d'), to : now()->format('Y-m-d'),
                    language : 'en', sort_by : 'popularity',
                    page_size: $pageSize, page : $currentPage
                );
                $hasMore = $articles->totalResults > $currentPage * $pageSize;
                foreach (array_chunk($articles->articles, 25) as $chunk) {
                    SaveNewsApiArticles::dispatch($chunk);
                }
                $this->info("Fetched page: $currentPage");
                $currentPage++;
                sleep(1);
            } catch (\Exception $e) {
                $this->error('We have got this error: '.$e->getMessage());
            }
        }
    }
}
