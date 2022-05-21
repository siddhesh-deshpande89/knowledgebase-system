<?php

declare(strict_types=1);
namespace KnowledgeSystem\Application\Controllers\Articles;

use Illuminate\Http\JsonResponse;
use KnowledgeSystem\Application\Controllers\Controller;

class CreateArticleController extends Controller {

    public function __invoke(): JsonResponse
    {
        // TODO: Implement __invoke() method.
        return new JsonResponse(['data'=>'test']);
    }
}
