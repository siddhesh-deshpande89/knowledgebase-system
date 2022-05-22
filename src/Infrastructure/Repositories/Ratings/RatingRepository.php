<?php

declare(strict_types=1);

namespace KnowledgeSystem\Infrastructure\Repositories\Ratings;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use KnowledgeSystem\Infrastructure\Models\ArticleRating;
use KnowledgeSystem\Infrastructure\Repositories\BaseRepository;

class RatingRepository extends BaseRepository implements RatingRepositoryInterface
{
    /**
     * ArticleRepository constructor.
     *
     * @param ArticleRating $model
     */
    public function __construct(ArticleRating $model)
    {
        parent::__construct($model);
    }

    public function getUserRatingByArticleId(int $articleId, string $ipAddress): Collection
    {
        return $this->where([
                                'article_id' => $articleId,
                                'ip_address' => $ipAddress,
                            ])->get();
    }

    public function getUserRatingDailyQuota(string $ipAddress): int
    {
        return $this->model->where('ip_address', $ipAddress)->where('created_at','>=', Carbon::yesterday())->count();
    }
}
