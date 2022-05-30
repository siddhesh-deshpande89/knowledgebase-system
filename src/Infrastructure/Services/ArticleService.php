<?php

declare(strict_types=1);

namespace KnowledgeSystem\Infrastructure\Services;

use KnowledgeSystem\Application\DTO\SearchCriteriaDTO;
use KnowledgeSystem\Application\Services\ArticleServiceInterface;
use KnowledgeSystem\Infrastructure\Models\Article;
use KnowledgeSystem\Infrastructure\Repositories\Articles\ArticleRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticleService implements ArticleServiceInterface
{
    private ArticleRepositoryInterface $repository;

    public function __construct(ArticleRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getArticle(int $articleId): Article
    {
        $article = $this->repository->find($articleId);

        if (empty($article)) {
            throw new NotFoundHttpException('Could not find the article id');
        }

        return $article;
    }

    public function createArticle(array $data): Article
    {
       return $this->repository->createArticle($data);
    }

    public function getArticles(SearchCriteriaDTO $searchCriteria) {
        return $this->repository->getArticles($searchCriteria);
    }
}
