<?php

declare(strict_types=1);

namespace KnowledgeSystem\Infrastructure\Providers;

use KnowledgeSystem\Application\Services\ArticleServiceInterface;
use KnowledgeSystem\Application\Services\RatingServiceInterface;
use KnowledgeSystem\Infrastructure\Services\ArticleService;
use KnowledgeSystem\Infrastructure\Services\RatingService;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->bind(ArticleServiceInterface::class, ArticleService::class);
        $this->app->bind(RatingServiceInterface::class, RatingService::class);
    }
}
