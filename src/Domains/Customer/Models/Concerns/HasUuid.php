<?php

declare(strict_types=1);

namespace Domains\Customer\Models\Concerns;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

trait HasUuid
{
    public static function bootHasUuid(): void
    {
        static::creating(fn (Model $model) => $model->uuid = Str::uuid()->toString());
    }
}
