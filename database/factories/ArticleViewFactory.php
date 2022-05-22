<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use KnowledgeSystem\Infrastructure\Models\ArticleView;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory
 */
class ArticleViewFactory extends Factory
{
    protected $model = ArticleView::class;

    public function definition()
    {
        return [
            'view_count' => rand(2, 100),
        ];
    }
}
