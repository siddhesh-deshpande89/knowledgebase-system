<?php

declare(strict_types=1);

namespace KnowledgeSystem\Infrastructure\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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

    public function getArticles(SearchCriteriaDTO $searchCriteria): LengthAwarePaginator
    {
        return $this->repository->getArticles($searchCriteria);
    }

    /**
     * @param int    $articleId
     * @param string $ipAddress
     *
     * @return bool
     */
    public function updateArticleViewCount(int $articleId, string $ipAddress): bool
    {
        $article = $this->repository->find($articleId);
        $guestViewCount = $this->repository->getArticleGuestView($article, $ipAddress);

        if ($guestViewCount > 0) {
            return false;
        }

        $this->storeViewCount($article, $ipAddress);

        return true;
    }

    /**
     * @param \KnowledgeSystem\Infrastructure\Models\Article $article
     * @param string                                         $ipAddress
     *
     * @return void
     */
    private function storeViewCount(Article $article, string $ipAddress): void
    {
        $this->repository->createArticleGuestView($article, ['ip_address' => $ipAddress]);
        $articleTodayViewCount = $this->repository->getTodayViewCount($article);

        if ($articleTodayViewCount > 0) {
            $this->repository->updateTodayArticleViewCount($article);

            return;
        }

        $this->repository->createTodayArticleViewCount($article);
    }
}
