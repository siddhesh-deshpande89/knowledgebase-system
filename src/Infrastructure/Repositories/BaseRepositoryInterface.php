<?php

declare(strict_types=1);

namespace KnowledgeSystem\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
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
     * @param $id
     *
     * @return Builder
     */
    public function where($condition): Builder;
}
