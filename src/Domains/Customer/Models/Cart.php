<?php

declare(strict_types=1);

namespace Domains\Customer\Models;

use Domains\Customer\Models\User;
use Database\Factories\CartFactory;
use Domains\Customer\States\Statuses\CartStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use JustSteveKing\KeyFactory\Models\Concerns\HasKey;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasKey;
    use HasFactory;

    protected $fillable = [
        'key',
        'status',
        'coupon',
        'total',
        'reduction',
        'user_id',
    ];

    protected $casts = [
        'status'    =>  CartStatus::class . ':nullable',
    ];

    protected static function newFactory(): Factory
    {
        return new CartFactory();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id'
        );
    }
}
