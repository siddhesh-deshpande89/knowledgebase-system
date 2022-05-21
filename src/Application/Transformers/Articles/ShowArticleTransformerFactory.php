<?php

declare(strict_types=1);

namespace KnowledgeSystem\Application\Transformers\Articles;

use KnowledgeSystem\Infrastructure\Models\Article;
use League\Fractal\Resource\Item;

class ShowArticleTransformerFactory
{
    public function create(Article $article): Item
    {
        return new Item($article, new ShowArticleTransformer());
    }
}
