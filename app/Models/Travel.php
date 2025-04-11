<?php

namespace App\Models;

use App\QueryFilter;
use App\Traits\SetUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Travel extends Model
{
    use SoftDeletes, HasFactory, SetUuid;

    protected $table = 'travels';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'destination',
        'departure_date',
        'return_date',
    ];

    public function order(): MorphOne
    {
        return $this->morphOne(Order::class, 'orderable');
    }

    public function scopeFilter(Builder $builder, QueryFilter $filters): Builder
    {
        return $filters->apply($builder);
    }
}
