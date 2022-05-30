<?php

declare(strict_types=1);

namespace KnowledgeSystem\Infrastructure\Repositories\Articles;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use KnowledgeSystem\Application\DTO\SearchCriteriaDTO;
use KnowledgeSystem\Infrastructure\Models\Article;
use KnowledgeSystem\Infrastructure\Models\ArticleView;
use KnowledgeSystem\Infrastructure\Repositories\BaseRepository;

class ArticleRepository extends BaseRepository implements ArticleRepositoryInterface
{

    /**
     * ArticleRepository constructor.
     *
     * @param Article $model
     */
    public function __construct(Article $model)
    {
        parent::__construct($model);
    }

    /**
     * @param $data
     *
     * @return \KnowledgeSystem\Infrastructure\Models\Article
     */
    public function createArticle($data): Article
    {
        $article = $this->create($data);
        $article->categories()->sync($data['categories']);

        return $article;
    }

    /**
     * @param \KnowledgeSystem\Application\DTO\SearchCriteriaDTO $searchCriteria
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getArticles(SearchCriteriaDTO $searchCriteria): LengthAwarePaginator
    {
        return $this->model->select(['id', 'title', 'weighted_average', 'created_at', 'updated_at'])
            ->addSelect(['view_count' => $this->buildViewsQuery($searchCriteria)])
            ->with('categories')
            ->filterCategories($searchCriteria->category_ids)
            ->filterCreatedDate($searchCriteria->created_from, $searchCriteria->created_to)
            ->sortArticles($searchCriteria->type, $searchCriteria->sort)
            ->paginate($this->paginateCount);
    }

    /**
     * @param \KnowledgeSystem\Application\DTO\SearchCriteriaDTO $searchCriteria
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function buildViewsQuery(SearchCriteriaDTO $searchCriteria): Builder
    {
        return ArticleView::selectRaw('sum(article_views.view_count)')
            ->filterViewsDate($searchCriteria->views_from, $searchCriteria->views_to)
            ->whereColumn('article_id', 'articles.id')
            ->groupBy('article_id');
    }
}
