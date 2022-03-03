<?php

declare(strict_types=1);

namespace Domains\Shared\Models\Concerns;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

trait HasUuid
{
    public static function bootHasUuid(): void
    {
        static::creating(fn (Model $model) => $model->uuid = Str::uuid()->toString());
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
