<?php

declare(strict_types=1);

namespace KnowledgeSystem\Infrastructure\Providers;

use KnowledgeSystem\Infrastructure\Services\ArticleService;
use KnowledgeSystem\Application\Services\ArticleServiceInterface;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->bind(ArticleServiceInterface::class, ArticleService::class);
    }

    public function provides(): array
    {
        return [
            ArticleServiceInterface::class
        ];
    }
}
