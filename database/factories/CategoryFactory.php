<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use KnowledgeSystem\Infrastructure\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->words(rand(3, 5), true),
        ];
    }
}
