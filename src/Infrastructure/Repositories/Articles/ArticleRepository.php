<?php

declare(strict_types=1);

namespace KnowledgeSystem\Infrastructure\Repositories\Articles;

use KnowledgeSystem\Infrastructure\Models\Article;
use KnowledgeSystem\Infrastructure\Repositories\BaseRepository;

class ArticleRepository extends BaseRepository implements ArticleRepositoryInterface
{
    /**
     * ArticleRepository constructor.
     *
     * @param Article $model
     */
    public function __construct(Article $model)
    {
        parent::__construct($model);
    }
}
