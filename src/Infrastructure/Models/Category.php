<?php

declare(strict_types=1);

namespace KnowledgeSystem\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['title'];

    protected $visible = ['id', 'title'];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Article::class);
    }
}
