<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use KnowledgeSystem\Infrastructure\Models\Article;
use KnowledgeSystem\Infrastructure\Models\ArticleRating;

class ArticleRatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        $articleIds = Article::inRandomOrder()->limit(500)->pluck('id');

        foreach ($articleIds as $articleId) {
            $ratings = [];
            for ($i = 0; $i < 20; $i++) {
                $ratings[] = [
                    'article_id' => $articleId,
                    'rating'     => rand(1, 5),
                    'ip_address' => $faker->ipv4(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }
            ArticleRating::insert($ratings);
        }
    }
}
