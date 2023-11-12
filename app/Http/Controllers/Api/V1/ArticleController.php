<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\V1\SearchArticleRequest;
use App\Http\Resources\V1\ArticleResource;
use App\Services\ArticleService;

class ArticleController extends ApiController
{
    public function __construct(protected $articleService = null)
    {
        $this->articleService = app(ArticleService::class);
    }

    public function index(SearchArticleRequest $request)
    {
        $articles = $this->articleService->generateQuery();
        $this->articleService->applyFilter($articles, $request);
        $articles = $articles->paginate();

        return $this->generateResponse(ArticleResource::collection($articles), true);
    }
}
