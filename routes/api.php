<?php

use Illuminate\Support\Facades\Route;
use KnowledgeSystem\Application\Controllers\Articles\CreateArticleController;
use KnowledgeSystem\Application\Controllers\Articles\ViewArticleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'articles'], function () {
    Route::post('/create', CreateArticleController::class)->name('article.create');
    Route::get('/{articleId}', ViewArticleController::class)->name('article.show');
});
