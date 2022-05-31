<?php

declare(strict_types=1);

namespace KnowledgeSystem\Infrastructure\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use KnowledgeSystem\Application\Services\SearchServiceInterface;
use KnowledgeSystem\Infrastructure\Repositories\Articles\ArticleRepositoryInterface;

class SearchService implements SearchServiceInterface
{
    private ArticleRepositoryInterface $articleRepository;
    public function __construct(ArticleRepositoryInterface $articleRepository){
        $this->articleRepository = $articleRepository;
    }

    public function search(string $keyword): LengthAwarePaginator
    {
       return $this->articleRepository->searchQuery($keyword);
    }
}
