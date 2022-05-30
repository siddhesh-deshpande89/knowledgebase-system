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
            // Once an Article record has been created
            // Pick between 1 and 5 Category records in a random order
            // Associate the Article and Category records
            // $article->categories()
            //     ->attach(Category::inRandomOrder()->take(random_int(1, 10))->pluck('id'));
        });
    }
}
