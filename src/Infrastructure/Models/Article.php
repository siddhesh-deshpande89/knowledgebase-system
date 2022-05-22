<?php

declare(strict_types=1);

namespace KnowledgeSystem\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:dd/mm/yyyy',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(ArticleRating::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(ArticleView::class);
    }
}
