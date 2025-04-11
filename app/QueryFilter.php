<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    protected Builder $builder;

    protected array $sortable = [];

    public function __construct(protected Request $request)
    {}

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->request->all() as $field => $value) {
            if (method_exists($this, $field)) {
                $this->$field($value);
            }
        }

        return $this->builder;
    }

    protected function filter(array $arr): Builder
    {
        foreach ($arr as $field => $value) {
            if (method_exists($this, $field)) {
                $this->$field($value);
            }
        }

        return $this->builder;
    }

    protected function filterDate($value): Builder
    {
        if (empty($value)) {
            return $this->builder;
        }

        $dates = explode(' - ', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('date', $dates);
        }

        return $this->builder->whereDate('date', $value);
    }

    protected function sort(string $value): void
    {
        $attributes = explode(',', $value);

        foreach ($attributes as $attribute) {
            $direction = 'asc';

            if (str_starts_with($attribute, '-')) {
                $direction = 'desc';
                $attribute = substr($attribute, 1);
            }

            if (!in_array($attribute, $this->sortable) && !array_key_exists($attribute, $this->sortable)) {
                continue;
            }

            $columnName = $this->sortable[$attribute] ?? null;

            if ($columnName === null) {
                $columnName = $attribute;
            }

            $this->builder->orderBy($columnName, $direction);
        }
    }
}
