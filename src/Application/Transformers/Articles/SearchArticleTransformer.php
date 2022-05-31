<?php

declare(strict_types=1);

namespace KnowledgeSystem\Application\Transformers\Articles;

use League\Fractal\TransformerAbstract;

class SearchArticleTransformer extends TransformerAbstract
{
    public function transform($article): array
    {
        return [
            'id'             => $article->id,
            'title'          => $article->title,
            'body'          => $article->body,
            'average_rating' => $article->weighted_average,
            'categories'     => $this->transformCategories($article->categories),
            'view_count'     => $article->view_count ?? 0,
            'created_at'     => $article->created_at,
            'updated_at'     => $article->updated_at,
        ];
    }

    private function transformCategories($categories): array
    {
        return collect($categories)->map(function ($category) {
            return [
                'id'     => $category->id,
                'title' => $category->title,
            ];
        })->all();
    }
}
