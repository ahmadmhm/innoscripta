<?php

namespace App\Console\Commands\Sources;

use App\Jobs\SaveGuardianNewsArticles;
use Illuminate\Console\Command;
use Illuminate\Http\Client\PendingRequest;

class FetchGuardianNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'source:fetch-guardian-news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'With this command you`ll get the latest Guardian news';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (! config('datasource.guardian.api_key')) {
            $this->error('There is no api key available for The Guardian');

            return;
        }

        $currentPage = 1;
        $hasMore = true;
        $pageSize = config('datasource.fetch.page_size');
        while ($hasMore) {
            try {
                $articles = app(PendingRequest::class)
                    ->withQueryParameters([
                        'page-size' => $pageSize,
                        'page' => $currentPage,
                        'api-key' => config('datasource.guardian.api_key'),
                        'order-by' => 'newest',
                        'show-fields' => 'all',
                        'from-date' => now()->format('Y-m-d'),
                        'to-date' => now()->format('Y-m-d'),
                    ])->get(config('datasource.guardian.url'));
                if ($articles->ok()) {
                    $data = $articles->json()['response'];

                    $hasMore = $data['total'] > $currentPage * $pageSize;
                    foreach (array_chunk($data['results'], 25) as $chunk) {
                        SaveGuardianNewsArticles::dispatch($chunk);
                    }
                    $this->info("Fetched page: $currentPage");
                    $currentPage++;
                } else {
                    $this->error($articles->status());
                }
                sleep(2);
            } catch (\Exception $e) {
                $this->error('We have got this error: '.$e->getMessage());
            }
        }
    }
}
