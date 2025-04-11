<?php

namespace App\Filters;

use App\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

class OrdersFilter extends QueryFilter
{
    protected array $sortable = [
        'status',
        'created_at',
    ];

    public function createdAt($value): Builder
    {
        return $this->filterDate($value);
    }

    public function status($value): Builder
    {
        return $this->builder->where('status', $value);
    }
}
