<?php

declare(strict_types=1);

namespace KnowledgeSystem\Application\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class ArticleRatingDTO extends DataTransferObject
{
    public int $article_id;
    public string $ip_address;
    public int $rating;
}
