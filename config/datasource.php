<?php

return [
    'newsapi' => [
        'api_key' => env('NEWS_API_KEY'),
        'queue' => 'newsapi',
    ],
    'guardian' => [
        'api_key' => env('GUARDIAN_API_KEY'),
        'url' => env('GUARDIAN_API_URL', 'http://content.guardianapis.com/search'),
        'queue' => 'guardian',
    ],
    'new_york_times' => [
        'api_key' => env('NEW_YORK_TIMES_API_KEY'),
        'url' => env('NEW_YORK_TIMES_API_URL', 'https://api.nytimes.com/svc/search/v2/articlesearch.json'),
        'page_size' => 10,
        'queue' => 'new_york_times',
    ],
    'fetch' => [
        'page_size' => 100,
    ],
];
