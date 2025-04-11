<?php

namespace App\Models;

use App\QueryFilter;
use App\Traits\SetUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    use SoftDeletes, HasFactory, SetUuid;

    protected $table = 'orders';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'status',
        'orderable_id',
        'orderable_type',
        'requester_id'
    ];

    public function orderable(): MorphTo
    {
        return $this->morphTo();
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function scopeFilter(Builder $builder, QueryFilter $filters): Builder
    {
        return $filters->apply($builder);
    }

    public function canUserUpdateOrder(): bool
    {
        return Auth::user()->id !== $this->requester_id || false;
    }
}
