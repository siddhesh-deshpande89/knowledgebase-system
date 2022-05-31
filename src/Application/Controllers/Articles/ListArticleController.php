<?php

declare(strict_types=1);

namespace KnowledgeSystem\Application\Controllers\Articles;

use KnowledgeSystem\Application\Controllers\Controller;
use KnowledgeSystem\Application\DTO\SearchCriteriaDTO;
use KnowledgeSystem\Application\Requests\ListArticleRequest;
use KnowledgeSystem\Application\Services\ArticleServiceInterface;
use KnowledgeSystem\Application\Transformers\Articles\ListArticleTransformerFactory;
use KnowledgeSystem\Application\Transformers\IlluminatePaginateAdapter;
use League\Fractal\Manager;

class ListArticleController extends Controller
{

    private ArticleServiceInterface $articleService;
    private Manager $manager;

    public function __construct(
        ArticleServiceInterface $articleService,
        Manager $manager,
        ListArticleTransformerFactory $collectionFactory
    ) {
        $this->articleService = $articleService;
        $this->manager = $manager;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @OA\Get(
     *     summary="List articles and also filter them",
     *     tags={"Article"},
     *     security={{"bearerAuth":{}}},
     *     path="/articles/list",
     *     @OA\Parameter(
     *         name="category_ids",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *         in="query",
     *         description="filter by category ids",
     *         example="1,2,3,4",
     *         required=false,
     *     ),
     *     @OA\Parameter(
     *         name="created_from",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *         in="query",
     *         description="created from date",
     *         example="2022-05-22",
     *         required=false,
     *     ),
     *     @OA\Parameter(
     *         name="created_to",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *         in="query",
     *         description="created to date",
     *         example="2022-05-23",
     *         required=false,
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *         in="query",
     *         description="sort by id, views, popularity",
     *         example="desc",
     *         required=false,
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *         in="query",
     *         description="type = views or popularity",
     *         example="views",
     *         required=false,
     *     ),
     *     @OA\Parameter(
     *         name="views_from",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *         in="query",
     *         description="filter views from date",
     *         example="2022-05-22",
     *         required=false,
     *     ),
     *     @OA\Parameter(
     *         name="views_to",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *         in="query",
     *         description="filter views to date",
     *         example="2022-05-23",
     *         required=false,
     *     ),
     *     @OA\Response(response=200,description="Success",
     *     @OA\MediaType(
     *          mediaType="application/json",
     *          example={
     *           "data": {{
     *              "id": 1,
     *              "title": "Article title",
     *              "categories": {{"id":1,"title":"test category1"},{"id":7,"title":"test category7"}},
     *              "created_at": "2022-05-28T19:07:31.000000Z",
     *              "updated_at": "2022-05-28T19:07:31.000000Z"
     *           }},
     *           "meta":{
     *            "pagination": {
                     "total": 11,
     *     "count": 11,
     *     "per_page": 10,
     *     "current_page": 2,
     *     "total_pages": 2,
     *     "links": { "previous": "http://localhost/api/articles/list?page=1", "next": "http://localhost/api/articles/list?page=3" }
*                 }}
     *       }
     *     )
     *    ),
     *    @OA\Response(response=404,description="Invalid url",
     *     @OA\MediaType(
     *          mediaType="application/json",
     *          example={
     *              "message": "Request is unsuccessful: 404",
     *          }
     *     )
     *    ),
     * )
     */
    public function __invoke(ListArticleRequest $request)
    {
        $articles = $this->articleService->getArticles(new SearchCriteriaDTO($request->all()));
        $resource = $this->collectionFactory->create($articles->getCollection());
        $resource->setPaginator(new IlluminatePaginateAdapter($articles));
        $data = $this->manager->createData($resource);

        return response()->json($data);
    }
}
