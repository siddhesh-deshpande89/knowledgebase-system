<?php

declare(strict_types=1);

namespace KnowledgeSystem\Application\Services;

interface SearchServiceInterface
{
    public function search(string $keyword);
}
