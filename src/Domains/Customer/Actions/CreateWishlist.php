<?php

declare(strict_types=1);

namespace Domains\Customer\Actions;

use Domains\Customer\Models\Wishlist;
use Illuminate\Database\Eloquent\Model;
use Domains\Customer\ValueObjects\WishlistValueObject;

class CreateWishlist
{
    public static function handle(WishlistValueObject $object): Model
    {
        return Wishlist::query()->create([
            'name'  =>  $object->name,
            'public'  =>  $object->public,
            'user_id'  =>  $object->user,
        ]);
    }
}
