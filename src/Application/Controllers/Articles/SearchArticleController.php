<?php

declare(strict_types=1);

namespace KnowledgeSystem\Application\Controllers\Articles;

use KnowledgeSystem\Application\Controllers\Controller;
use KnowledgeSystem\Application\Requests\SearchArticleRequest;
use KnowledgeSystem\Application\Services\SearchServiceInterface;
use KnowledgeSystem\Application\Transformers\Articles\SearchArticleTransformerFactory;
use KnowledgeSystem\Application\Transformers\IlluminatePaginateAdapter;
use League\Fractal\Manager;

class SearchArticleController extends Controller
{
    private SearchServiceInterface $searchService;
    private Manager $manager;
    private SearchArticleTransformerFactory $collectionFactory;

    public function __construct(
        SearchServiceInterface $searchService,
        Manager $manager,
        SearchArticleTransformerFactory $collectionFactory
    )
    {
        $this->searchService = $searchService;
        $this->manager = $manager;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @OA\Get(
     *     summary="Search article title and body using search keyword",
     *     tags={"Article"},
     *     security={{"bearerAuth":{}}},
     *     path="/articles/search",
     *     @OA\Parameter(
     *         name="keyword",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *         in="query",
     *         description="filter by category ids",
     *         example="hello%20world",
     *         required=true,
     *     ),
     *     @OA\Response(response=200,description="Success",
     *     @OA\MediaType(
     *          mediaType="application/json",
     *          example={
     *           "data": {{
     *              "id": 1,
     *              "title": "Article title",
     *              "body": "this is hello - world tutorial",
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
    public function __invoke(SearchArticleRequest $request)
    {
        $articles = $this->searchService->search($request->keyword);
        $resource = $this->collectionFactory->create($articles->getCollection());
        $resource->setPaginator(new IlluminatePaginateAdapter($articles));
        $data = $this->manager->createData($resource);
        return response()->json($data);
    }
}
