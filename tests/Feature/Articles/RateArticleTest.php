<?php

declare(strict_types=1);

namespace Tests\Feature\Articles;

use Carbon\Carbon;
use Illuminate\Support\Facades\Queue;
use KnowledgeSystem\Infrastructure\Jobs\RecalculateRatingJob;
use KnowledgeSystem\Infrastructure\Models\Article;
use KnowledgeSystem\Infrastructure\Models\ArticleRating;
use KnowledgeSystem\Infrastructure\Models\Category;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

/**
 * @group Article
 */
class RateArticleTest extends TestCase
{
    const ENDPOINT = 'api/articles/rate';

    /**
     * @test
     * @dataProvider Tests\Data\RateArticleDataProvider::provideInvalidRateArticleInput
     */
    public function shouldThrowValidationErrorOnInvalidInput($data, $expectedErrors)
    {
        Article::factory()->count(1)->create();
        $response = $this->postJson(self::ENDPOINT, $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors($expectedErrors);
    }

    /**
     * @test
     */
    public function shouldThrowHttpExceptionIfUserAlreadyRatedArticle()
    {
        Article::factory()
            ->has(ArticleRating::factory()->count(1), 'ratings')
            ->count(1)
            ->create();

        $articleRating = ArticleRating::first();
        $this->serverVariables = ['REMOTE_ADDR' => $articleRating->ip_address];
        $response = $this->postJson(self::ENDPOINT, [
            'article_id' => $articleRating->article_id,
            'rating'     => $articleRating->rating,
        ]);

        $responseData = json_decode($response->getContent());

        $this->assertEquals('You have already rated this article.', $responseData->message);
        $this->assertDatabaseCount(ArticleRating::class, 1);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionIfUserRated10ArticlesInSameDay()
    {
        // We will create 10 articles and 10 ratings by same user
        // He should not be allowed to make rating on the same day
        $ipAddress = $this->faker->ipv4();
        Article::factory()
            ->count(11)
            ->create();

        $articleIds = Article::limit(10)->get()->pluck('id');
        $articleIds->each(function ($articleId) use ($ipAddress) {
            ArticleRating::factory()->state(['article_id' => $articleId, 'ip_address' => $ipAddress])->count(1)->create(
            );
        });

        $this->serverVariables = ['REMOTE_ADDR' => $ipAddress];
        $response = $this->postJson(self::ENDPOINT, [
            'article_id' => 11,
            'rating'     => rand(1, 5),
        ]);

        $responseData = json_decode($response->getContent());

        $this->assertEquals(
            'You have voted many articles in the last 24 hours. Please try again tomorrow.',
            $responseData->message
        );
        $this->assertDatabaseCount(ArticleRating::class, 10);
        $this->assertDatabaseMissing(ArticleRating::class, ['article_id' => 11, 'ip_address' => $ipAddress]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @test
     */
    public function shouldRateArticleSuccessfullyNextDayAfterRating10TimesInPreviousDay()
    {
        // User added reviews on Jan 1st
        $ipAddress = $this->faker->ipv4();
        Article::factory()
            ->count(11)
            ->create();

        $articleIds = Article::limit(10)->get()->pluck('id');
        $articleIds->each(function ($articleId) use ($ipAddress) {
            ArticleRating::factory()->state(['article_id' => $articleId, 'ip_address' => $ipAddress])->count(1)->create(
            );
        });


        // On Jan 2nd he tries to add 11th review
        Carbon::setTestNow(Carbon::createSafe(2021, 1, 2, 12, 27, 00));

        $this->serverVariables = ['REMOTE_ADDR' => $ipAddress];
        $response = $this->postJson(self::ENDPOINT, [
            'article_id' => 11,
            'rating'     => rand(1, 5),
        ]);

        $responseData = json_decode($response->getContent());

        $this->assertEquals('Article has been rated successfully.', $responseData->message);
        $this->assertDatabaseCount(ArticleRating::class, 11);
        $this->assertDatabaseHas(ArticleRating::class, ['article_id' => 11, 'ip_address' => $ipAddress]);
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function shouldDispatchRecalculateRatingJobSuccessfully()
    {
        Queue::fake();
        Article::factory()->count(1)->create();

        $ipAddress = $this->faker->ipv4();
        $this->serverVariables = ['REMOTE_ADDR' => $ipAddress];
        $response = $this->postJson(self::ENDPOINT, [
            'article_id' => 1,
            'rating'     => rand(1, 5),
        ]);

        Queue::assertPushed(RecalculateRatingJob::class);

        $responseData = json_decode($response->getContent());
        $this->assertEquals('Article has been rated successfully.', $responseData->message);
        $this->assertDatabaseCount(ArticleRating::class, 1);
        $this->assertDatabaseHas(ArticleRating::class, ['article_id' => 1, 'ip_address' => $ipAddress]);
        $response->assertStatus(Response::HTTP_OK);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->setFakeTime();
    }
}
