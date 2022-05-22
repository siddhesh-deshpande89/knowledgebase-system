<?php

declare(strict_types=1);

namespace KnowledgeSystem\Infrastructure\Repositories\Ratings;

use Illuminate\Database\Eloquent\Collection;

interface RatingRepositoryInterface
{
    public function getUserRatingByArticleId(int $articleId, string $ipAddress): Collection;
    public function getUserRatingDailyQuota(string $ipAddress): int;
}
