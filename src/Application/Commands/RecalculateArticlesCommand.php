<?php

namespace KnowledgeSystem\Application\Commands;

use Illuminate\Console\Command;
use KnowledgeSystem\Application\Services\RatingServiceInterface;
use KnowledgeSystem\Infrastructure\Models\Article;

class RecalculateArticlesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:recalculate_ratings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate rating for given article ids';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(
        RatingServiceInterface $ratingService
    ) {
        // can modify later for bulk recalculate
        Article::orderBy('id')->chunk(1, function ($articles) use ($ratingService) {
            $ratingService->recalculateWeightedAverageRating($articles[0]->id);
        });

        return 0;
    }
}
