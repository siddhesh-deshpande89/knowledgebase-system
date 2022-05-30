<?php

declare(strict_types=1);

namespace KnowledgeSystem\Application\Services;

interface RatingServiceInterface
{
    public function validateRating(int $articleId, string $ipAddress): bool;

    public function recalculateWeightedAverageRating(int $articleId): float;
}
