<?php

declare(strict_types=1);
namespace KnowledgeSystem\Application\Controllers\Articles;

use Illuminate\Http\JsonResponse;
use KnowledgeSystem\Application\Controllers\Controller;

class ViewArticleController extends Controller {

    public function __invoke(): JsonResponse
    {
        return new JsonResponse(['data'=>'test']);
    }
}
