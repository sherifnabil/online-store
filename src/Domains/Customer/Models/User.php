<?php

declare(strict_types=1);

namespace Domains\Customer\Models;

use Domains\Customer\Models\Cart;
use Laravel\Sanctum\HasApiTokens;
use Database\Factories\UserFactory;
use Illuminate\Notifications\Notifiable;
use Domains\Shared\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use HasFactory;
    use HasApiTokens;
    use HasUuid;

    protected $fillable = [
        'uuid',
        'first_name',
        'last_name',
        'email',
        'password',
       'billing_id',
       'shipping_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function shipping(): BelongsTo
    {
        return $this->belongsTo(
            related: Address::class,
            foreignKey: 'shipping_id'
        );
    }

    public function billing(): BelongsTo
    {
        return $this->belongsTo(
            related: Address::class,
            foreignKey: 'billing_id'
        );
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(
            related: Address::class,
            foreignKey: 'user_id'
        );
    }

    public function cart(): HasOne
    {
        return $this->hasOne(
            related: Cart::class,
            foreignKey: 'user_id'
        );
    }

    protected static function newFactory(): Factory
    {
        return new UserFactory();
    }
}
