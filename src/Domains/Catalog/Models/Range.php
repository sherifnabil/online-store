<?php

declare(strict_types=1);

namespace Domains\Catalog\Models;

use Database\Factories\RangeFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use JustSteveKing\KeyFactory\Models\Concerns\HasKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Range extends Model
{
    use HasKey;
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'key',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    protected static function newFactory(): Factory
    {
        return new RangeFactory();
    }
}
