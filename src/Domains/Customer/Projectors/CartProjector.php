<?php

declare(strict_types=1);

namespace Domains\Customer\Projectors;

use Illuminate\Support\Str;
use Domains\Customer\Models\Cart;
use Domains\Customer\Models\Coupon;
use Domains\Customer\Models\CartItem;
use Illuminate\Database\Eloquent\Model;
use Domains\Customer\Events\CouponWasAppied;
use Domains\Customer\Aggregates\CartAggregate;
use Domains\Customer\Events\DecreaseCartQuantity;
use Domains\Customer\Events\IncreaseCartQuantity;
use Domains\Customer\Events\ProductWasAddedToCart;
use Domains\Customer\Events\ProductWasRemovedFromCart;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class CartProjector extends Projector
{
    public function onProductWasAddedToCart(ProductWasAddedToCart $event): void
    {
        $cart = Cart::query()->find(
            $event->cartID
        );

        $item = $cart->items()->create([
            'purchasable_id'    =>  $event->purchasableID,
            'purchasable_type'  =>  $event->type,
        ]);

        $cart->update([
            'total' =>  $item->purchasable->retail
        ]);
    }

    public function onProductWasRemovedFromCart(ProductWasRemovedFromCart $event): void
    {
        $cart = Cart::query()->find(
            $event->cartID
        );

        $items = CartItem::query()
            ->with(['purchasable'])
            ->get();

        $item =  $items->filter(
            fn (Model $item) =>
            $item->id === $event->purchasableID
        )
        ->first();

        if ($cart->count() === 1) {
            $cart->update([
                'total' => 0
            ]);
        } else {
            $cart->update([
                'total' => ($cart->total - $item->purchasable->retail)
            ]);
        }

        $cart
            ->items()
            ->where('purchasable_id', $item->purchasable->id)
            ->where('purchasable_type', strtolower(class_basename($item->purchasable)))
            ->delete();
    }

    public function onIncreaseCartQuantity(IncreaseCartQuantity $event): void
    {
        $item = CartItem::query()
        ->where(
            'cart_id',
            $event->cartID
        )->where(
            'id',
            $event->cartItemID
        )->first();

        $item->update([
            'quantity'  => ($item->quantity + $event->quantity)
        ]);
    }

    public function onDecreaseCartQuantity(DecreaseCartQuantity $event): void
    {
        $item = CartItem::query()
        ->where(
            'cart_id',
            $event->cartID
        )
        ->where(
            'id',
            $event->cartItemID
        )->first();


        if ($event->quantity >= $item->quantity) {
            CartAggregate::retrieve(
                uuid: Str::uuid()->toString()
            )->removeProduct(
                purchasableID: $item->purchasable->id,
                cartID: $item->cart_id,
                type: get_class($item->purchasable)
            );
            return;
        }

        $item->update([
            'quantity'  => ($item->quantity - $event->quantity)
        ]);
    }

    public function onCouponWasAppied(CouponWasAppied $event): void
    {
        $coupon = Coupon::query()->where('code', $event->code)->first();

        $cart = Cart::query()->find($event->cartID)
        ->update([
            'coupon'        =>  $coupon->code,
            'reduction'     =>  $coupon->reduction,
        ]);
    }
}
