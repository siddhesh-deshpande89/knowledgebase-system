<?php

declare(strict_types=1);

namespace KnowledgeSystem\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleView extends Model
{
    use HasFactory;
    protected $fillable = ['view_count'];


    public function scopeFilterViewsDate(Builder $query, ?string $viewsFrom, ?string $viewsTo) {

        if (!empty($viewsFrom)) {
            $query = $query->whereDate('created_at', '>=', $viewsFrom);
        }

        if (!empty($viewsTo)) {
            $query = $query->whereDate('created_at', '<=', $viewsTo);
        }

        return $query;
    }
}
