<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

final class OrderByBlockAmountScope implements Scope
{
    public function __construct(
        protected string $direction = 'desc',
    ) {}

    public function apply(Builder $builder, Model $model)
    {
        $builder->orderBy('total_amount', $this->direction);
    }
}
