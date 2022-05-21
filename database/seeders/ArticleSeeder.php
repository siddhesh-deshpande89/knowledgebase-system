<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use KnowledgeSystem\Infrastructure\Models\Article;
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
            ->count(1000)->create();
    }
}