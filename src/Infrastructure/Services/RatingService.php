<?php

declare(strict_types=1);

namespace KnowledgeSystem\Infrastructure\Services;

use KnowledgeSystem\Application\Services\RatingServiceInterface;
use KnowledgeSystem\Infrastructure\Repositories\Ratings\RatingRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RatingService implements RatingServiceInterface
{
    private RatingRepositoryInterface $ratingRepository;
    const RATINGS_DAILY_LIMIT = 10;

    public function __construct(
        RatingRepositoryInterface $ratingRepository
    ) {
        $this->ratingRepository = $ratingRepository;
    }

    public function validateRating(int $articleId, string $ipAddress): bool
    {
        // Has user rated article before
       $this->validateUserArticleRating($articleId, $ipAddress);

        // Has user rated > 10 times in the last day
        $this->validateUserRatingsDailyQuota($ipAddress);

        return true;
    }

    private function validateUserArticleRating(int $articleId, string $ipAddress)
    {
        $userRatedArticleBefore = $this->ratingRepository->getUserRatingByArticleId($articleId, $ipAddress);

        if ($userRatedArticleBefore->isNotEmpty()) {
            throw new BadRequestHttpException('You have already rated this article');
        }
    }

    private function validateUserRatingsDailyQuota(string $ipAddress)
    {
        $userRatingsDaily = $this->ratingRepository->getUserRatingDailyQuota($ipAddress);

        if ($userRatingsDaily > self::RATINGS_DAILY_LIMIT) {
            throw new BadRequestHttpException(
                'You have voted many articles in the last 24 hours. Please try again tomorrow.'
            );
        }
    }
}
