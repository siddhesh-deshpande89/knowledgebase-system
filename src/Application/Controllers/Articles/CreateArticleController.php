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

    public function __invoke(CreateArticleRequest $request): JsonResponse
    {
        $article = $this->articleService->createArticle($request->all());

        $item = $this->itemFactory->create($article);
        $data = $this->manager->createData($item)->toArray();

        return response()->json($data);
    }
}
