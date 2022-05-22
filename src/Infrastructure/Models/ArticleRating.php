<?php

declare(strict_types=1);

namespace KnowledgeSystem\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleRating extends Model
{
    use HasFactory;

    public function articles(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
