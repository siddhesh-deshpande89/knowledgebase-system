<?php

declare(strict_types=1);

namespace Tests\Feature\Articles;

use KnowledgeSystem\Infrastructure\Models\Article;
use KnowledgeSystem\Infrastructure\Models\Category;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class SearchArticleTest extends TestCase
{
    const ENDPOINT = 'api/articles/search';

    /**
     * @test
     */
    public function shouldGiveValidationErrorIfSearchKeywordIsMissing()
    {
        $response = $this->getJson(self::ENDPOINT);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['keyword' => ['The keyword field is required.']]);
    }

    /**
     * @test
     */
    public function shouldSearchArticleByTitleCorrectly()
    {
        Article::factory()->has(Category::factory()->count(1))->state(
            ['title' => 'test title inventore with dummy words harum']
        )->create();

        $response = $this->getJson(self::ENDPOINT . '?keyword=title');
        $result = json_decode($response->getContent(), true);
        $this->assertEquals(1, count($result['data']));
    }

    /**
     * @test
     */
    public function shouldSearchArticleByBodyCorrectly()
    {
        Article::factory()->has(Category::factory()->count(1))->state(
            ['body' => 'the quick brown fox']
        )->create();

        Article::factory(Category::factory()->count(1))->state(
            ['body' => 'test body inventore with dummy words harum']
        )->create();

        $response = $this->getJson(self::ENDPOINT . '?keyword=inventore');
        $result = json_decode($response->getContent(), true);
        $this->assertEquals(1, count($result['data']));
    }

    /**
     * @test
     */
    public function shouldReturnEmptyResultOnInvalidSearch()
    {
        Article::factory()->has(Category::factory()->count(1))->state(
            ['body' => 'test body inventore with dummy words harum']
        )->create();

        $response = $this->getJson(self::ENDPOINT . '?keyword=different');
        $result = json_decode($response->getContent(), true);
        $this->assertEquals(0, count($result['data']));
    }
}
