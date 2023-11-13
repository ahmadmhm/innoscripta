<?php

namespace App\Console\Commands\Sources;

use App\Jobs\SaveNewYorkTimesArticles;
use Illuminate\Console\Command;
use Illuminate\Http\Client\PendingRequest;

class FetchNewYorkTimesApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'source:fetch-new-york-times';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'With this command you`ll get the latest NewYorkTimes news';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (! config('datasource.new_york_times.api_key')) {
            $this->error('There is no api key available for The NewYorkTimes');

            return;
        }

        $currentPage = 1;
        $hasMore = true;
        while ($hasMore) {
            try {
                $articles = app(PendingRequest::class)
                    ->withQueryParameters([
                        'page' => $currentPage,
                        'api-key' => config('datasource.new_york_times.api_key'),
                        'sort' => 'newest',
                        'begin_date' => now()->format('Ymd'),
                        'end_date' => now()->format('Ymd'),
                    ])->get(config('datasource.new_york_times.url'));
                if ($articles->ok()) {
                    $data = $articles->json()['response'];
                    $hasMore = $data['meta']['hits'] > $data['meta']['offset'];
                    SaveNewYorkTimesArticles::dispatch($data['docs']);

                    $this->info("Fetched page: $currentPage");
                    $currentPage++;
                    sleep(1);
                } else {
                    $this->error('Request error : '.$articles->status());
                }
            } catch (\Exception $e) {
                $this->error('We have got this error: '.$e->getMessage());
            }
        }
    }
}
