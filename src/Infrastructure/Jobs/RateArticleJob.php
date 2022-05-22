<?php

namespace KnowledgeSystem\Infrastructure\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class RateArticleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $articleId, int $rating)
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // TODO
    }
}
