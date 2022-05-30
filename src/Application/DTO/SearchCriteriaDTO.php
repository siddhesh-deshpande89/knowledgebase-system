<?php

declare(strict_types=1);

namespace KnowledgeSystem\Application\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class SearchCriteriaDTO extends DataTransferObject
{
    public ?string $type;
    public ?string $sort = 'asc';

    public ?string $created_from;
    public ?string $created_to;

    public ?string $category_ids;
    public ?string $views_from;
    public ?string $views_to;

}
