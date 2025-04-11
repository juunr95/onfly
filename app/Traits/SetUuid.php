<?php

namespace App\Traits;

use Ramsey\Uuid\Uuid;

/**
 * This trait should only be used in models.
 */
trait SetUuid
{
    /**
     * Implements creating callback to assign UUID before persists in database.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating( fn ($model) => $model->{$model->getKeyName()} = Uuid::uuid4()->toString());
    }
}
