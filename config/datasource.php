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
    'fetch' => [
        'page_size' => 100,
    ],
];
