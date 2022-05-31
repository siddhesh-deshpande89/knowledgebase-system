<?php

declare(strict_types=1);

namespace KnowledgeSystem\Application\Controllers\Articles;

use Illuminate\Http\JsonResponse;
use KnowledgeSystem\Application\Controllers\Controller;
use KnowledgeSystem\Application\DTO\ArticleRatingDTO;
use KnowledgeSystem\Application\Requests\StoreRatingRequest;
use KnowledgeSystem\Application\Services\RatingServiceInterface;
use KnowledgeSystem\Infrastructure\Jobs\RecalculateRatingJob;

class RateArticleController extends Controller
{
    private RatingServiceInterface $ratingService;

    public function __construct(
        RatingServiceInterface $ratingService
    ) {
        $this->ratingService = $ratingService;
    }

    /**
     * @OA\Post(
     *     summary="Rate an article",
     *     tags={"Article"},
     *     security={{"bearerAuth":{}}},
     *     path="/articles/rate",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/StoreRatingRequest",
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200,description="Success",
     *     @OA\MediaType(
     *          mediaType="application/json",
     *          example={
     *              "message": "Article has been rated successfully."
     *          }
     *     )
     *    ),
     *     @OA\Response(response=400,description="Bad Request",
     *     @OA\MediaType(
     *          mediaType="application/json",
     *          example={
     *              "message": "You have already rated this article."
     *          }
     *     )
     *    ),
     * )
     */
    public function __invoke(StoreRatingRequest $request): JsonResponse
    {
        $articleRating = $this->ratingService->addRating(
            new ArticleRatingDTO(array_merge($request->all(), ['ip_address' => $request->ip()]))
        );

        RecalculateRatingJob::dispatch($articleRating->article_id);

        return response()->json(['message' => __('api_messages.article_rate_success')]);
    }
}
