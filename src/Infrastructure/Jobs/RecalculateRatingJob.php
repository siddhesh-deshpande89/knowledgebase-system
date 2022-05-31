<?php

namespace KnowledgeSystem\Infrastructure\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use KnowledgeSystem\Application\Services\RatingServiceInterface;

class RecalculateRatingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    private $articleId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $articleId)
    {
        $this->articleId = $articleId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(RatingServiceInterface $ratingService)
    {
        $ratingService->recalculateWeightedAverageRating($this->articleId);
    }
}
