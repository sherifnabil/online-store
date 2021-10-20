<?php

declare(strict_types=1);

namespace Domains\Customer\Models;

use Database\Factories\LocationFactory;
use Illuminate\Database\Eloquent\Model;
use Domains\Customer\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasUuid;
    use HasFactory;

    protected $fillable = [
        'uuid',
        'house',
        'street',
        'parish',
        'ward',
        'district',
        'country',
        'postcode',
        'county',
    ];

    protected static function newFactory(): Factory
    {
        return new LocationFactory();
    }
}
