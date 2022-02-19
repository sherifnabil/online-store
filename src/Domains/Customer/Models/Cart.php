<?php

declare(strict_types=1);

namespace Domains\Customer\Models;

use Domains\Customer\Models\User;
use Database\Factories\CartFactory;
use Domains\Customer\States\Statuses\CartStatus;
use Domains\Shared\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use JustSteveKing\KeyFactory\Models\Concerns\HasKey;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasUuid;
    use HasFactory;

    protected $fillable = [
        'uuid',
        'status',
        'coupon',
        'total',
        'reduction',
        'user_id',
    ];

    protected $casts = [
        'status'    =>  CartStatus::class . ':nullable',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id'
        );
    }

    public function items(): HasMany
    {
        return $this->hasMany(
            related: CartItem::class,
            foreignKey:'cart_id'
        );
    }

    protected static function newFactory(): Factory
    {
        return new CartFactory();
    }
}
