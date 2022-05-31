<?php

declare(strict_types=1);

namespace KnowledgeSystem\Application\Transformers\Articles;

use League\Fractal\Resource\Collection;

class ListArticleTransformerFactory
{
    public function create($articles): Collection
    {
        return new Collection($articles, new ListArticleTransformer());
    }
}
