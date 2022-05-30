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
     * @param \KnowledgeSystem\Application\Requests\ListArticleRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
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
