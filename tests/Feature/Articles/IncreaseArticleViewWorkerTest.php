<?php

declare(strict_types=1);

namespace Tests\Feature\Articles;

use KnowledgeSystem\Application\Services\ArticleServiceInterface;
use KnowledgeSystem\Infrastructure\Jobs\IncreaseArticleViewJob;
use KnowledgeSystem\Infrastructure\Models\Article;
use KnowledgeSystem\Infrastructure\Models\ArticleGuestView;
use KnowledgeSystem\Infrastructure\Models\ArticleView;
use KnowledgeSystem\Infrastructure\Models\Category;
use Tests\TestCase;

class IncreaseArticleViewWorkerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldIncreaseViewCountCorrectly()
    {
        $article = Article::factory()->has(Category::factory()->count(2))->create();
        $ipAddress = '127.0.0.1';

        $articleService = $this->app->make(ArticleServiceInterface::class);

        $worker = new IncreaseArticleViewJob($article->id, $ipAddress);
        $worker->handle($articleService);

        $this->assertDatabaseHas(ArticleGuestView::class, ['article_id' => $article->id]);
    }

    /**
     * @test
     */
    public function shouldUpdateExistingViewCountCorrectly()
    {
        $article = Article::factory()->has(Category::factory()->count(2))
            ->has(ArticleView::factory()->count(1),'views')
            ->create();
        $ipAddress = '127.0.0.1';

        $articleService = $this->app->make(ArticleServiceInterface::class);

        $worker = new IncreaseArticleViewJob($article->id, $ipAddress);
        $worker->handle($articleService);

        $this->assertDatabaseHas(ArticleGuestView::class, ['article_id' => $article->id]);
    }

    /**
     * @test
     */
    public function shouldNotAddGuestViewIfExistsOnArticle()
    {
        $article = Article::factory()->has(Category::factory()->count(2))
            ->has(ArticleGuestView::factory()->state(['ip_address' => '127.0.0.1']),'guest_views')
            ->has(ArticleView::factory()->count(1),'views')
            ->create();
        $ipAddress = '127.0.0.1';

        $articleService = $this->app->make(ArticleServiceInterface::class);

        $worker = new IncreaseArticleViewJob($article->id, $ipAddress);
        $worker->handle($articleService);

        $this->assertDatabaseCount(ArticleGuestView::class, 1);
    }
}
