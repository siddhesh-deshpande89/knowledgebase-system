<?php

declare(strict_types=1);

namespace Tests\Feature\Articles;

use Illuminate\Support\Facades\Artisan;
use KnowledgeSystem\Infrastructure\Models\Article;
use KnowledgeSystem\Infrastructure\Models\ArticleRating;
use KnowledgeSystem\Infrastructure\Models\ArticleView;
use KnowledgeSystem\Infrastructure\Models\Category;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

/**
 * @group Article
 */
class ListArticleTest extends TestCase
{
    const ENDPOINT = 'api/articles/list';

    /**
     * @test
     */
    public function shouldReturn10ArticlesPerPageCorrectly()
    {
        Category::factory()->count(5)->create();
        Article::factory()->count(11)->afterCreating(function(Article $article){
            $article->categories()
                ->attach(Category::inRandomOrder()->take(random_int(1, 5))->pluck('id'));
        })->create();
        $response = $this->getJson(self::ENDPOINT);

        $data = Article::with('categories')->limit(10)->get();

        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(10, count($data));
        $response->assertJsonStructure([
                                           'data' => [
                                               [
                                                   'id',
                                                   'title',
                                                   'view_count',
                                                   'average_rating',
                                                   'created_at',
                                                   'updated_at',
                                                   'categories' => [['id', 'title']],
                                               ],
                                           ],
                                           'meta' => [
                                               'pagination' => [
                                                   'current_page',
                                                   'per_page',
                                                   'total',
                                                   'count',
                                                   'total_pages',
                                               ],
                                           ],
                                       ]);
    }

    /**
     * @test
     */
    public function shouldFilterRecordsByCategoryIdsCorrectly()
    {
        // Article 1,2,3 belong to different categories
        // Article 2 belongs to 3,4,5 category so it should be output
        Category::factory()->count(5)->create();
        Article::factory()->count(3)->create();

        Article::find(1)->categories()->attach([1,2,3]);
        Article::find(2)->categories()->attach([3,4,5]);
        Article::find(3)->categories()->attach([1,2,3]);

        $response = $this->getJson(self::ENDPOINT . '?category_ids=3,4,5');
        $result = json_decode($response->getContent(), true);

        $this->assertEquals(2, $result['data'][0]['id']);
        $this->assertEquals(1, count($result['data']) );
    }

    /**
     * @test
     */
    public function shouldFilterRecordsBasedOnPopularityCorrectly()
    {
        // Article1 has 1 review of 5 stars
        Article::factory()
            ->has(ArticleRating::factory()->state(['rating' => 5])->count(1), 'ratings')
            ->count(1)
            ->create();

        // Article2 has 2 review of 4 stars.
        Article::factory()
            ->has(ArticleRating::factory()->state(['rating' => 4])->count(2), 'ratings')
            ->count(1)
            ->create();

        // recalculate ratings same as job
        Artisan::call('articles:recalculate_ratings');

        $response = $this->getJson(self::ENDPOINT . '?type=popularity&sort=desc');
        $result = json_decode($response->getContent(), true);

        // Article 2 should be desc
        $this->assertEquals(2, $result['data'][0]['id']);
        $this->assertEquals(1, $result['data'][1]['id']);
    }

    /**
     * @test
     */
    public function shouldReturnAllRelevantArticlesIfCategoryIdsIsNotPassed()
    {
        // Article 1,2,3 belong to different categories
        // Article 2 belongs to 3,4,5 category so it should be output
        Category::factory()->count(5)->create();
        Article::factory()->count(3)->create();

        Article::find(1)->categories()->attach([1,2,3]);
        Article::find(2)->categories()->attach([3,4,5]);
        Article::find(3)->categories()->attach([1,2,3]);

        $response = $this->getJson(self::ENDPOINT . '?category_ids=');
        $result = json_decode($response->getContent(), true);

        $this->assertEquals(1, $result['data'][0]['id']);
        $this->assertEquals(2, $result['data'][1]['id']);
        $this->assertEquals(3, $result['data'][2]['id']);
        $this->assertEquals(3, count($result['data']) );
    }

    /**
     * @test
     */
    public function shouldReturnEmptyResultIfCategoryIdsInputIsInValid()
    {
        // Article 1,2,3 belong to different categories
        // Article 2 belongs to 3,4,5 category so it should be output
        Category::factory()->count(5)->create();
        Article::factory()->count(3)->create();

        Article::find(1)->categories()->attach([1,2,3]);
        Article::find(2)->categories()->attach([3,4,5]);
        Article::find(3)->categories()->attach([1,2,3]);

        $response = $this->getJson(self::ENDPOINT . '?category_ids='.$this->faker->sentence);
        $result = json_decode($response->getContent(), true);

        $this->assertEquals(0, count($result['data']) );
    }

    /**
     * @test
     */
    public function shouldReturnArticlesCreatedFromCorrectly()
    {
        // Some articles exist in March
        // Created from filter is 22 May 2022
        Article::factory()->has(Category::factory()->count(1))->state(['created_at' => '2022-03-15'])->count(3)->create();
        Article::factory()->has(Category::factory()->count(2))->state(['created_at' => '2022-04-09'])->count(3)->create();
        Article::factory()->has(Category::factory()->count(3))->state(['created_at' => '2022-05-23'])->count(3)->create();
        Article::factory()->has(Category::factory()->count(4))->state(['created_at' => '2022-05-24'])->count(3)->create();

        $response = $this->getJson(self::ENDPOINT . '?created_from=2022-05-22');
        $result = json_decode($response->getContent(), true);
        $this->assertEquals(6, count($result['data']) );
    }

    /**
     * @test
     */
    public function shouldReturnArticlesCreatedToCorrectly()
    {
        // Some articles exist in March
        // Created from filter is 22 May 2022
        Article::factory()->has(Category::factory()->count(1))->state(['created_at' => '2022-03-15'])->count(3)->create();
        Article::factory()->has(Category::factory()->count(2))->state(['created_at' => '2022-04-09'])->count(2)->create();
        Article::factory()->has(Category::factory()->count(3))->state(['created_at' => '2022-05-23'])->count(3)->create();
        Article::factory()->has(Category::factory()->count(4))->state(['created_at' => '2022-05-24'])->count(3)->create();

        $response = $this->getJson(self::ENDPOINT . '?created_to=2022-05-22');
        $result = json_decode($response->getContent(), true);
        $this->assertEquals(5, count($result['data']) );
    }

    /**
     * @test
     */
    public function shouldReturnArticleSortedDescByViewsCorrectly()
    {
        // Article 1 has 6000 views on 3 dates
        // Article 2 has 9000 views on same 3 dates
        // Article 3 has 5000 views on same 3 dates
        // sort desc should list article 2 first, then 1 and then 3

        Article::factory()
            ->has(ArticleView::factory()->state(['view_count' => 2000,'created_at' => '2022-05-22'])->count(1),'views')
            ->has(ArticleView::factory()->state(['view_count' => 2000,'created_at' => '2022-05-23'])->count(1),'views')
            ->has(ArticleView::factory()->state(['view_count' => 2000,'created_at' => '2022-05-24'])->count(1),'views')
            ->count(1)->create();

        Article::factory()
            ->has(ArticleView::factory()->state(['view_count' => 3000,'created_at' => '2022-05-22'])->count(1),'views')
            ->has(ArticleView::factory()->state(['view_count' => 3000,'created_at' => '2022-05-23'])->count(1),'views')
            ->has(ArticleView::factory()->state(['view_count' => 3000,'created_at' => '2022-05-24'])->count(1),'views')
            ->count(1)->create();

        Article::factory()
            ->has(ArticleView::factory()->state(['view_count' => 2000,'created_at' => '2022-05-22'])->count(1),'views')
            ->has(ArticleView::factory()->state(['view_count' => 2000,'created_at' => '2022-05-23'])->count(1),'views')
            ->has(ArticleView::factory()->state(['view_count' => 1000,'created_at' => '2022-05-24'])->count(1),'views')
            ->count(1)->create();

        $response = $this->getJson(self::ENDPOINT . '?type=views&sort=desc');
        $result = json_decode($response->getContent(), true);

        $this->assertEquals(2, $result['data'][0]['id']);
        $this->assertEquals(1, $result['data'][1]['id']);
        $this->assertEquals(3, $result['data'][2]['id']);
    }

    /**
     * @test
     */
    public function shouldReturnArticleSortedAscByViewsCorrectly()
    {
        // Article 1 has 6000 views on 3 dates
        // Article 2 has 9000 views on same 3 dates
        // Article 3 has 5000 views on same 3 dates
        // sort desc should list article 3 first, then 1 and then 2

        Article::factory()
            ->has(ArticleView::factory()->state(['view_count' => 2000,'created_at' => '2022-05-22'])->count(1),'views')
            ->has(ArticleView::factory()->state(['view_count' => 2000,'created_at' => '2022-05-23'])->count(1),'views')
            ->has(ArticleView::factory()->state(['view_count' => 2000,'created_at' => '2022-05-24'])->count(1),'views')
            ->count(1)->create();

        Article::factory()
            ->has(ArticleView::factory()->state(['view_count' => 3000,'created_at' => '2022-05-22'])->count(1),'views')
            ->has(ArticleView::factory()->state(['view_count' => 3000,'created_at' => '2022-05-23'])->count(1),'views')
            ->has(ArticleView::factory()->state(['view_count' => 3000,'created_at' => '2022-05-24'])->count(1),'views')
            ->count(1)->create();

        Article::factory()
            ->has(ArticleView::factory()->state(['view_count' => 2000,'created_at' => '2022-05-22'])->count(1),'views')
            ->has(ArticleView::factory()->state(['view_count' => 2000,'created_at' => '2022-05-23'])->count(1),'views')
            ->has(ArticleView::factory()->state(['view_count' => 1000,'created_at' => '2022-05-24'])->count(1),'views')
            ->count(1)->create();

        $response = $this->getJson(self::ENDPOINT . '?type=views');
        $result = json_decode($response->getContent(), true);

        $this->assertEquals(3, $result['data'][0]['id']);
        $this->assertEquals(1, $result['data'][1]['id']);
        $this->assertEquals(2, $result['data'][2]['id']);
    }

    /**
     * @test
     */
    public function shoudReturnResultsBasedOnViewsFrom()
    {
        Article::factory()
            ->has(ArticleView::factory()->state(['view_count' => 2000,'created_at' => '2022-05-22'])->count(1),'views')
            ->has(ArticleView::factory()->state(['view_count' => 2000,'created_at' => '2022-05-23'])->count(1),'views')
            ->has(ArticleView::factory()->state(['view_count' => 1000,'created_at' => '2022-05-24'])->count(1),'views')
            ->count(1)->create();

        $response = $this->getJson(self::ENDPOINT . '?type=views&views_from=2022-05-23');
        $result = json_decode($response->getContent(), true);

        $this->assertEquals(3000, $result['data'][0]['view_count']);
    }

    /**
     * @test
     */
    public function shoudReturnResultsBasedOnViewsTo()
    {
        Article::factory()
            ->has(ArticleView::factory()->state(['view_count' => 2000,'created_at' => '2022-05-22'])->count(1),'views')
            ->has(ArticleView::factory()->state(['view_count' => 2000,'created_at' => '2022-05-23'])->count(1),'views')
            ->has(ArticleView::factory()->state(['view_count' => 1000,'created_at' => '2022-05-24'])->count(1),'views')
            ->count(1)->create();

        $response = $this->getJson(self::ENDPOINT . '?type=views&views_to=2022-05-23');
        $result = json_decode($response->getContent(), true);

        $this->assertEquals(4000, $result['data'][0]['view_count']);
    }
}
