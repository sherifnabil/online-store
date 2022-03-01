<?php

declare(strict_types=1);

namespace Domains\Fulfillment\Models;

use Database\Factories\OrderFactory;
use Domains\Customer\Models\OrderLine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use JustSteveKing\KeyFactory\Models\Concerns\HasKey;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasKey;
    use HasFactory;

    protected $fillable = [
        'key',
        'number',
        'state',
        'coupon',
        'total',
        'reduction',
        'completed_at',
        'cancelled_at',
        'user_id',
        'shipping_id',
        'billing_id',
    ];

    protected $casts = [
        'shipping_id'   =>  'integer',
        'billing_id'    =>  'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id'
        );
    }

    public function shipping(): BelongsTo
    {
        return $this->belongsTo(
            related: Location::class,
            foreignKey: 'shipping_id'
        );
    }

    public function billing(): BelongsTo
    {
        return $this->belongsTo(
            related: Location::class,
            foreignKey: 'billing_id'
        );
    }

    public function lineItems(): HasMany
    {
        return $this->hasMany(
            related: OrderLine::class,
            foreignKey: 'order_id'
        );
    }

    protected static function newFactory(): Factory
    {
        return OrderFactory::new();
    }
}
