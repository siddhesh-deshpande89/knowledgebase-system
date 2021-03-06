<?php

namespace KnowledgeSystem\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use KnowledgeSystem\Infrastructure\Repositories\Articles\ArticleRepository;
use KnowledgeSystem\Infrastructure\Repositories\Articles\ArticleRepositoryInterface;
use KnowledgeSystem\Infrastructure\Repositories\BaseRepository;
use KnowledgeSystem\Infrastructure\Repositories\BaseRepositoryInterface;
use KnowledgeSystem\Infrastructure\Repositories\Ratings\RatingRepository;
use KnowledgeSystem\Infrastructure\Repositories\Ratings\RatingRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
        $this->app->bind(RatingRepositoryInterface::class, RatingRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
