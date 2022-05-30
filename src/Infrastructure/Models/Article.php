<?php

declare(strict_types=1);

namespace KnowledgeSystem\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use KnowledgeSystem\Infrastructure\Scopes\GlobalScopes\ActiveScope;

class Article extends Model
{

    private const TYPE_VIEWS = 'views';
    private const TYPE_POPULARITY = 'popularity';

    use HasFactory;

    protected $fillable = ['title', 'body','weighted_average'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new ActiveScope);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null                           $createdFrom
     * @param string|null                           $createdTo
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterCreatedDate(Builder $query, ?string $createdFrom, ?string $createdTo): Builder
    {
        if (!empty($createdFrom)) {
            $query = $query->whereDate('created_at', '>=', $createdFrom);
        }

        if (!empty($createdTo)) {
            $query = $query->whereDate('created_at', '<=', $createdTo);
        }

        return $query;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null                           $categoryIds
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterCategories(Builder $query, ?string $categoryIds): Builder
    {
        if (!empty($categoryIds)) {
            $categoryIds = explode(',', $categoryIds);
            foreach ($categoryIds as $categoryId) {
                $query = $query->whereHas('categories', function ($q) use ($categoryId) {
                    return $q->where('category_id', $categoryId);
                });
            }
        }

        return $query;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null                           $type
     * @param string                                $sortOrder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortArticles(Builder $query, ?string $type, string $sortOrder): Builder
    {
        if ($type === self::TYPE_VIEWS) {
            return $query->orderBy('view_count', $sortOrder);
        }

        if ($type === self::TYPE_POPULARITY) {
            return $query->orderBy('weighted_average', $sortOrder);
        }

        return $query->orderBy('articles.id', empty($type) ? $sortOrder : 'ASC');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'article_category');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(ArticleRating::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function views(): HasMany
    {
        return $this->hasMany(ArticleView::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function guest_views(): HasMany
    {
        return $this->hasMany(ArticleGuestView::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCategories()
    {
        return $this->categories()->get();
    }
}
