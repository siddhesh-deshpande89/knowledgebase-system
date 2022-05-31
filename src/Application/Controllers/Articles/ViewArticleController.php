<?php

declare(strict_types=1);

namespace KnowledgeSystem\Application\Controllers\Articles;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use KnowledgeSystem\Application\Controllers\Controller;
use KnowledgeSystem\Application\Services\ArticleServiceInterface;
use KnowledgeSystem\Application\Transformers\Articles\ShowArticleTransformerFactory;
use KnowledgeSystem\Application\Validators\ShowArticleRequestValidator;
use KnowledgeSystem\Infrastructure\Jobs\IncreaseArticleViewJob;
use League\Fractal\Manager;

class ViewArticleController extends Controller
{
    private ArticleServiceInterface $articleService;
    private ShowArticleRequestValidator $requestValidator;
    private Manager $manager;
    private ShowArticleTransformerFactory $itemFactory;
    const QUEUE_NAME = 'ViewCounterQueue';

    public function __construct(
        ShowArticleRequestValidator $requestValidator,
        ArticleServiceInterface $articleService,
        Manager $manager,
        ShowArticleTransformerFactory $itemFactory,
    ) {
        $this->articleService = $articleService;
        $this->requestValidator = $requestValidator;
        $this->manager = $manager;
        $this->itemFactory = $itemFactory;
    }

    /**
     * @OA\Get(
     *     summary="View article by id",
     *     tags={"Article"},
     *     security={{"bearerAuth":{}}},
     *     path="/articles/{articleId}",
     *     @OA\Parameter(name="articleId",in="path",required=true),
     *     @OA\Response(response=200,description="Success",
     *     @OA\MediaType(
     *          mediaType="application/json",
     *          example={
     *           "data": {
     *              "id": 1,
     *              "title": "Article title",
     *              "body": "Article body goes here",
     *              "created_at": "2022-05-28T19:07:31.000000Z",
     *              "updated_at": "2022-05-28T19:07:31.000000Z"
     *           }
     *       }
     *     )
     *    ),
     *    @OA\Response(response=404,description="Invalid article id",
     *     @OA\MediaType(
     *          mediaType="application/json",
     *          example={
     *              "message": "Request is unsuccessful: 404",
     *          }
     *     )
     *    ),
     * )
     */
    public function __invoke($articleId, Request $request): JsonResponse
    {
        $articleId = $this->requestValidator->validate($articleId);
        $article = $this->articleService->getArticle($articleId);

        $item = $this->itemFactory->create($article);
        $data = $this->manager->createData($item)->toArray();

       IncreaseArticleViewJob::dispatch($articleId, $request->ip())->onQueue(self::QUEUE_NAME);

        return response()->json($data);
    }
}
