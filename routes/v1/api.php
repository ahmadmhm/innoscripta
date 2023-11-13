<?php

use Illuminate\Support\Facades\Route;

Route::group([], function () {
    Route::prefix('articles')->group(function () {
        Route::post('', [\App\Http\Controllers\Api\V1\ArticleController::class, 'index'])->middleware('cacheResponse:300');
    });
});
