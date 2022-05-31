<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use KnowledgeSystem\Infrastructure\Models\Article;
use KnowledgeSystem\Infrastructure\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'body' => $this->faker->text()
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Article $article) {

        });
    }
}
