<?php

declare(strict_types=1);

namespace KnowledgeSystem\Infrastructure\Services;

use KnowledgeSystem\Application\DTO\ArticleRatingDTO;
use KnowledgeSystem\Application\Services\RatingServiceInterface;
use KnowledgeSystem\Infrastructure\Models\ArticleRating;
use KnowledgeSystem\Infrastructure\Repositories\Articles\ArticleRepositoryInterface;
use KnowledgeSystem\Infrastructure\Repositories\Ratings\RatingRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RatingService implements RatingServiceInterface
{
    const RATINGS_DAILY_LIMIT = 10;
    private RatingRepositoryInterface $ratingRepository;
    private ArticleRepositoryInterface $articleRepository;

    public function __construct(
        RatingRepositoryInterface $ratingRepository,
        ArticleRepositoryInterface $articleRepository
    ) {
        $this->ratingRepository = $ratingRepository;
        $this->articleRepository = $articleRepository;
    }

    public function validateRating(int $articleId, string $ipAddress): bool
    {
        // Has user rated article before
        $this->validateUserArticleRating($articleId, $ipAddress);

        // Has user rated > 10 times in the last day
        $this->validateUserRatingsDailyQuota($ipAddress);

        return true;
    }

    public function addRating(ArticleRatingDTO $articleRatingDTO): ArticleRating
    {
        $this->validateRating($articleRatingDTO->article_id, $articleRatingDTO->ip_address);

        return $this->articleRepository->find($articleRatingDTO->article_id)->ratings()->create(
            $articleRatingDTO->toArray()
        );
    }

    private function validateUserArticleRating(int $articleId, string $ipAddress)
    {
        $userRatedArticleBefore = $this->ratingRepository->getUserRatingByArticleId($articleId, $ipAddress);

        if ($userRatedArticleBefore->isNotEmpty()) {
            throw new BadRequestHttpException('You have already rated this article.');
        }
    }

    private function validateUserRatingsDailyQuota(string $ipAddress)
    {
        $userRatingsDaily = $this->ratingRepository->getUserRatingDailyQuota($ipAddress);

        if ($userRatingsDaily >= self::RATINGS_DAILY_LIMIT) {
            throw new BadRequestHttpException(
                'You have voted many articles in the last 24 hours. Please try again tomorrow.'
            );
        }
    }

    public function recalculateWeightedAverageRating(int $articleId): float {
        $rating = $this->ratingRepository->getSumOfRatings($articleId);
        $weightedAverage = round($rating->weighted_average / 15,2);
        $this->articleRepository->find($articleId)->update(['weighted_average' => $weightedAverage]);

        return $weightedAverage;
    }
}
