<?php

namespace KnowledgeSystem\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body'];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
