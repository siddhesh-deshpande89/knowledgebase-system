<?php

declare(strict_types=1);

namespace KnowledgeSystem\Infrastructure\Scopes\GlobalScopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ActiveScope implements Scope
{

    const STATUS_ACTIVE = 1;

    public function apply(Builder $builder, Model $model)
    {
        $builder->where('active', self::STATUS_ACTIVE);
    }
}
