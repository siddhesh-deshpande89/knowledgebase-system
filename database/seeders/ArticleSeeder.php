<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use KnowledgeSystem\Infrastructure\Models\Article;
use KnowledgeSystem\Infrastructure\Models\ArticleRating;
use KnowledgeSystem\Infrastructure\Models\ArticleView;
use KnowledgeSystem\Infrastructure\Models\Category;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Article::factory()
            ->count(1000)->afterCreating(function(Article $article) {
                // Once an Article record has been created
                // Pick between 1 and 10 Category records in a random order
                // Associate the Article and Category records
                $article->categories()
                    ->attach(Category::inRandomOrder()->take(random_int(1, 10))->pluck('id'));
            })->create();
    }
}
