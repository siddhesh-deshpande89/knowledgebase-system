<?php

declare(strict_types=1);

namespace KnowledgeSystem\Infrastructure\Repositories\Articles;

use Illuminate\Database\Eloquent\Model;
use KnowledgeSystem\Infrastructure\Models\Article;
use KnowledgeSystem\Infrastructure\Models\ArticleGuestView;

interface ArticleRepositoryInterface
{
    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model;

    /**
     * @param $id
     * @return Model
     */
    public function find(int $id): ?Model;

    /**
     * @param Article    $article
     * @param string $ipAddress
     *
     * @return int
     */
    public function getArticleGuestView(Article $article, string $ipAddress): int;

    /**
     * @param \KnowledgeSystem\Infrastructure\Models\Article $article
     * @param                                                $data
     *
     * @return \KnowledgeSystem\Infrastructure\Models\ArticleGuestView
     */
    public function createArticleGuestView(Article $article, $data): ArticleGuestView;

    /**
     * @param \KnowledgeSystem\Infrastructure\Models\Article $article
     *
     * @return  int
     */
    public function getTodayViewCount(Article $article): int;

    /**
     * @param \KnowledgeSystem\Infrastructure\Models\Article $article
     *
     * @return int
     */
    public function createTodayArticleViewCount(Article $article): int;

    /**
     * @param \KnowledgeSystem\Infrastructure\Models\Article $article
     *
     * @return int
     */
    public function updateTodayArticleViewCount(Article $article): int;
}
