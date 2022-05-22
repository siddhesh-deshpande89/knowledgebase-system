<?php

declare(strict_types=1);

namespace KnowledgeSystem\Application\Controllers\Articles;

use KnowledgeSystem\Application\Controllers\Controller;
use KnowledgeSystem\Application\Requests\StoreRatingRequest;
use KnowledgeSystem\Application\Services\RatingServiceInterface;
use KnowledgeSystem\Infrastructure\Jobs\RateArticleJob;

class RateArticleController extends Controller
{
    private RatingServiceInterface $ratingService;
    public function __construct(
        RatingServiceInterface $ratingService
    ) {
        $this->ratingService = $ratingService;
    }

    public function __invoke(StoreRatingRequest $request)
    {
        $this->ratingService->validateRating($request->article_id, $request->ip());

        RateArticleJob::dispatch($request->article_id, $request->rating);
        return response()->json(['message' => 'Acknowledged']);
    }
}
