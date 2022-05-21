<?php

declare(strict_types=1);

namespace KnowledgeSystem\Application\Services;

interface ArticleServiceInterface
{
    public function getArticle(int $articleId);
}
