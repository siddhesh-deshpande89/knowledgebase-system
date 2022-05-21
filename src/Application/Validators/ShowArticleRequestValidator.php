<?php

declare(strict_types=1);

namespace KnowledgeSystem\Application\Validators;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShowArticleRequestValidator
{
    public function validate($articleId): int
    {
        $articleId = intval($articleId);
        if(empty($articleId))
        {
            throw new NotFoundHttpException('Invalid article id');
        }

        return $articleId;
    }
}
