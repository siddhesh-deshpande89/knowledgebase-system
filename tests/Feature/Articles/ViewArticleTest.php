<?php

declare(strict_types=1);

namespace Tests\Feature\Articles;

use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\BaseTestCase;

/**
 * @group Article
 */
class ViewArticleTest extends BaseTestCase
{
    /**
     * @test
     */
    public function shouldShowArticleContent()
    {
        $response = $this->getJson(sprintf('api/articles/%s', 1));
        $response->assertStatus(Response::HTTP_OK);
    }
}
