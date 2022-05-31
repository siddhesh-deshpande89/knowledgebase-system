<?php

declare(strict_types=1);

namespace KnowledgeSystem\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use KnowledgeSystem\Application\DTO\SearchCriteriaDTO;
use KnowledgeSystem\Infrastructure\Models\Article;

interface ArticleServiceInterface
{
    /**
     * @param int $articleId
     *
     * @return \KnowledgeSystem\Infrastructure\Models\Article
     */
    public function getArticle(int $articleId): Article;

    /**
     * @param array $data
     *
     * @return \KnowledgeSystem\Infrastructure\Models\Article
     */
    public function createArticle(array $data): Article;


    /**
     * @param \KnowledgeSystem\Application\DTO\SearchCriteriaDTO $searchCriteria
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getArticles(SearchCriteriaDTO $searchCriteria): LengthAwarePaginator;

    /**
     * @param int    $articleId
     * @param string $ipAddress
     *
     * @return bool
     */
    public function updateArticleViewCount(int $articleId, string $ipAddress): bool;
}
