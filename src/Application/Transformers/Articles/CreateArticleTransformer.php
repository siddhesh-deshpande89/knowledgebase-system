<?php

declare(strict_types=1);

namespace KnowledgeSystem\Application\Transformers\Articles;

use KnowledgeSystem\Infrastructure\Models\Article;
use League\Fractal\TransformerAbstract;

class CreateArticleTransformer extends TransformerAbstract
{
    public function transform(Article $article): array
    {
        return [
            'id'         => $article->id,
            'title'      => $article->title,
            'body'       => $article->body,
            'categories' => $article->getCategories(),
            'created_at' => $article->created_at,
            'updated_at' => $article->updated_at,
        ];
    }
}
