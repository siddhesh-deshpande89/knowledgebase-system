<?php

namespace KnowledgeSystem\Infrastructure\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use KnowledgeSystem\Application\Services\ArticleServiceInterface;

class IncreaseArticleViewJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    private int $articleId;
    private string $ipAddress;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $articleId, string $ipAddress)
    {
        $this->articleId = $articleId;
        $this->ipAddress = $ipAddress;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
        ArticleServiceInterface $articleService
    )
    {
        $articleService->updateArticleViewCount($this->articleId, $this->ipAddress);
    }
}
