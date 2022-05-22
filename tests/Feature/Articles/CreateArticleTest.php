<?php

declare(strict_types=1);

namespace Tests\Feature\Articles;

use KnowledgeSystem\Infrastructure\Models\Category;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

/**
 * @group Article
 */
class CreateArticleTest extends TestCase
{
    /**
     * @test
     * @dataProvider Tests\Data\CreateArticleDataProvider::provideInvalidArticleCreateInput
     */
    public function shouldThrowValidationErrorOnInvalidInput($data, $expectedErrors)
    {
        Category::factory()->count(1)->create();
        $response = $this->postJson('api/articles/create', $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors($expectedErrors);
    }

    /**
     * @test
     * @dataProvider Tests\Data\CreateArticleDataProvider::provideValidArticleCreateInput
     */
    public function shouldCreateArticleSuccessfullyOnValidInput($data)
    {
        $category = Category::factory()->count(1)->create()->first();
        $response = $this->postJson('api/articles/create', $data);

        $this->assertDatabaseCount('articles', 1);
        $this->assertDatabaseHas('articles', ['title' => $data['title']]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson([
                                       'data' => [
                                           'id'         => 1,
                                           'title'      => $data['title'],
                                           'body'       => $data['body'],
                                           'categories' => [
                                               [
                                                   'id'    => $category->id,
                                                   'title' => $category->title,
                                               ],
                                           ],
                                           'created_at' => $this->fakeCreatedTimeString,
                                           'updated_at' => $this->fakeCreatedTimeString,
                                       ],
                                   ]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->setFakeTime();
    }
}
