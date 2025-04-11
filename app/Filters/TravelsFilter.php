<?php

namespace App\Filters;

use App\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

class TravelsFilter extends QueryFilter
{
    protected array $sortable = [
        'destination',
        'departure_date',
        'return_date',
        'created_at'
    ];

    public function createdAt($value): Builder
    {
        return $this->filterDate($value);
    }

    public function destination($value): Builder
    {
        return $this->builder->where('destination', 'like', '%' . $value . '%');
    }

    public function departureDate($value): Builder
    {
        return $this->filterDate($value);
    }

    public function returnDate($value): Builder
    {
        return $this->filterDate($value);
    }
}
