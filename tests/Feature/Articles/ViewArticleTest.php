<?php

declare(strict_types=1);

namespace Tests\Feature\Articles;


use Illuminate\Support\Facades\Queue;
use KnowledgeSystem\Infrastructure\Jobs\IncreaseArticleViewJob;
use KnowledgeSystem\Infrastructure\Models\Article;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

/**
 * @group Article
 */
class ViewArticleTest extends TestCase
{
    /**
     * @test
     */
    public function shouldShowArticleContentCorrectly()
    {
        Queue::fake();
        $article = Article::factory(1)->create()->first();
        $response = $this->getJson(sprintf('api/articles/%s', $article->id));
        $response->assertExactJson([
                                       'data' => [
                                           'id'         => $article->id,
                                           'title'      => $article->title,
                                           'body'       => $article->body,
                                           'created_at' => $article->created_at,
                                           'updated_at' => $article->updated_at,
                                       ],
                                   ]);
        $response->assertStatus(Response::HTTP_OK);
        Queue::assertPushed(IncreaseArticleViewJob::class);
    }

    /**
     * @test
     */
    public function shouldReturnNotFoundHttpStatusOnMissingArticleIdInUrl()
    {
        $response = $this->getJson('api/articles');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * @dataProvider provideInvalidArticleIds
     * @test
     */
    public function shouldReturnNotFoundHttpStatusWithInvalidArticleIdInUrl($articleId)
    {
        Article::factory(1)->create()->first();
        $response = $this->getJson(sprintf('api/articles/%s', $articleId));
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    private function provideInvalidArticleIds(): array
    {
        return [
            'article id is 2'             => [2],
            'article id is empty string'  => [''],
            'article id is random string' => ['test'],
        ];
    }
}
