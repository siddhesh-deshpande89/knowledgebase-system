<?php

declare(strict_types=1);

namespace KnowledgeSystem\Application\Services;

use KnowledgeSystem\Infrastructure\Models\Article;

interface ArticleServiceInterface
{
    public function getArticle(int $articleId): Article;

    public function createArticle(array $data): Article;
}
