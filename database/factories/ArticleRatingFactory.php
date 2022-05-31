<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use KnowledgeSystem\Infrastructure\Models\Article;
use KnowledgeSystem\Infrastructure\Models\ArticleRating;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory
 */
class ArticleRatingFactory extends Factory
{
    protected $model = ArticleRating::class;

    public function definition()
    {
        return [
            'rating'     => rand(1, 5),
            'ip_address' => $this->faker->ipv4(),
        ];
    }

    public function testUser()
    {
        return $this->state(function (array $attributes) {
            return [
                'ip_address' => '54.65.32.22',
            ];
        });
    }
}
