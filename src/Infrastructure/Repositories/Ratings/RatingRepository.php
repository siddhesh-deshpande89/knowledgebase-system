<?php

declare(strict_types=1);

namespace KnowledgeSystem\Infrastructure\Repositories\Ratings;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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
        return $this->model->where('ip_address', $ipAddress)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->where('created_at', '<=', Carbon::now())
            ->count();
    }

    /**
     * Calculate sum of ratings count x rating
     * @param int $articleId
     *
     * @return Model
     */
    public function getSumOfRatings(int $articleId): Model
    {
        return $this->model->from(function($query) use ($articleId) {
            $query->selectRaw('count(rating) * rating as weight')
                ->from('article_ratings')
                ->where('article_id', $articleId)
                ->groupBy('rating');
        },'x')->selectRaw('sum(x.weight) as weighted_average')->first();
    }
}
