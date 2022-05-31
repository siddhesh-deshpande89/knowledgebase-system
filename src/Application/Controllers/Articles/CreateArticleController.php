<?php

declare(strict_types=1);

namespace KnowledgeSystem\Application\Controllers\Articles;

use Illuminate\Http\JsonResponse;
use KnowledgeSystem\Application\Controllers\Controller;
use KnowledgeSystem\Application\Requests\CreateArticleRequest;
use KnowledgeSystem\Application\Services\ArticleServiceInterface;
use KnowledgeSystem\Application\Transformers\Articles\CreateArticleTransformerFactory;
use League\Fractal\Manager;

class CreateArticleController extends Controller
{
    private ArticleServiceInterface $articleService;
    private Manager $manager;
    private CreateArticleTransformerFactory $itemFactory;

    public function __construct(
        ArticleServiceInterface $articleService,
        Manager $manager,
        CreateArticleTransformerFactory $itemFactory,
    ) {
        $this->articleService = $articleService;
        $this->manager = $manager;
        $this->itemFactory = $itemFactory;
    }

    /**
     * @OA\Post(
     *     summary="Create an article",
     *     tags={"Article"},
     *     security={{"bearerAuth":{}}},
     *     path="/articles/create",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/CreateArticleRequest",
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200,description="Success",
     *     @OA\MediaType(
     *          mediaType="application/json",
     *          example={
     *              "data": {
     *                  "id": "1",
     *                  "title": "the big brown fox",
     *                  "body": "this is a test",
     *                  "categories": {{"id": 1, "title": "test category1"}, {"id":2,"title":"test category2"}},
     *                  "created_at": "2022-05-30T18:23:42.000000Z",
     *                  "updated_at": "2022-05-30T18:23:42.000000Z"
     *     },
     *          }
     *     )
     *    ),
     * )
     */
    public function __invoke(CreateArticleRequest $request): JsonResponse
    {
        $article = $this->articleService->createArticle($request->all());

        $item = $this->itemFactory->create($article);
        $data = $this->manager->createData($item)->toArray();

        return response()->json($data);
    }
}
