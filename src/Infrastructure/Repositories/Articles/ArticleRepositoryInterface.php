<?php

declare(strict_types=1);

namespace KnowledgeSystem\Infrastructure\Repositories\Articles;

use Illuminate\Database\Eloquent\Model;

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
}
