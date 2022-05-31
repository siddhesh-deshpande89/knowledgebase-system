<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use KnowledgeSystem\Infrastructure\Models\Article;
use KnowledgeSystem\Infrastructure\Models\ArticleGuestView;
use KnowledgeSystem\Infrastructure\Models\ArticleView;

class ArticleViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $articleIds = Article::inRandomOrder()->limit(800)->pluck('id');
        $this->seedGuestViews($articleIds);
        $this->updateViewCount($articleIds);
    }

    private function seedGuestViews($articleIds)
    {
        $faker = Factory::create();
        foreach ($articleIds as $articleId) {
            $guestViews = [];
            for ($i = 1; $i <= 125; $i++) {
                $guestViews[] = [
                    'article_id' => $articleId,
                    'ip_address' => $faker->ipv4(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
            ArticleGuestView::insert($guestViews);
        }
    }

    private function updateViewCount($articleIds)
    {
        $batchInsert = [];
        foreach ($articleIds as $articleId) {
            $viewCount = ArticleGuestView::where('article_id', $articleId)->count();
            $batchInsert[] = [
                'article_id' => $articleId,
                'view_count' => $viewCount,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        ArticleView::insert($batchInsert);
    }
}
