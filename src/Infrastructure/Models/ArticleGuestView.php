<?php

declare(strict_types=1);

namespace KnowledgeSystem\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleGuestView extends Model
{
    use HasFactory;
    protected $fillable = ['ip_address'];
}
