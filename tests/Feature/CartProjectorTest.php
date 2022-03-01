<?php

use Domains\Customer\Models\Cart;
use Domains\Catalog\Models\Variant;
use Domains\Customer\Events\CouponWasAppied;
use Domains\Customer\Models\CartItem;
use Domains\Customer\Projectors\CartProjector;
use Domains\Customer\Events\DecreaseCartQuantity;
use Domains\Customer\Events\IncreaseCartQuantity;
use Domains\Customer\Events\ProductWasAddedToCart;
use Domains\Customer\Events\ProductWasRemovedFromCart;
use Domains\Customer\Models\Coupon;
use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;

beforeEach(fn () => $this->projector = new CartProjector());

it('can add a product to the cart', function () {
    expect($this->projector)->toBeInstanceOf(CartProjector::class);

    $event = new ProductWasAddedToCart(
        purchasableID: Variant::factory()->create()->id,
        cartID: Cart::factory()->create(['total' => 0])->id,
        type: 'variant'
    );

    $cart = Cart::query()->with(['items.purchasable'])->find($event->cartID);

    expect($cart->items->count())
    ->toEqual(0);

    expect($cart->total)
    ->toEqual(0);

    $this->projector->onProductWasAddedToCart(
        event: $event
    );

    expect($cart->refresh()->items->count())
    ->toEqual(1);

    expect($cart->total)
    ->toEqual($cart->items->first()->purchasable->retail);

    // dd($event);
});

it('can remove a product from a cart', function () {
    expect($this->projector)->toBeInstanceOf(CartProjector::class);

    $event = new ProductWasRemovedFromCart(
        purchasableID:  Variant::factory()->create()->id,
        cartID:         CartItem::factory()->create()->cart_id,
        type:           'variant'
    );

    $cart = Cart::query()->with(['items.purchasable'])->find($event->cartID);

    expect($cart->items->count())
    ->toEqual(1);

    $this->projector->onProductWasRemovedFromCart(
        event: $event
    );

    $cart->refresh();

    expect($cart->items->count())
    ->toEqual(0);

    expect($cart->total)
    ->toEqual(0);
});

it('can increase the quantity of an item in cart ', function () {
    expect($this->projector)->toBeInstanceOf(CartProjector::class);

    $event = new IncreaseCartQuantity(
        cartID:         $cartId = Cart::factory()->create()->id,
        cartItemID:     CartItem::factory()->create(['cart_id'  =>  $cartId, 'quantity' => 1])->id,
        quantity:       1
    );

    $cart = Cart::query()->find($event->cartID);

    expect($cart->items->first()->quantity)->toEqual(1);

    $this->projector->onIncreaseCartQuantity(
        event: $event
    );

    expect($cart->refresh()->items->first()->quantity)->toEqual(2);
});

it('can decrease the quantity of an item in cart ', function () {
    expect($this->projector)->toBeInstanceOf(CartProjector::class);

    $event = new DecreaseCartQuantity(
        cartID:         $cartId = Cart::factory()->create()->id,
        cartItemID:     CartItem::factory()->create(['cart_id'  =>  $cartId, 'quantity' => 3])->id,
        quantity:       1
    );

    $cart = Cart::query()->find($event->cartID);

    expect($cart->items->first()->quantity)->toEqual(3);

    $this->projector->onDecreaseCartQuantity(
        event: $event
    );

    expect($cart->refresh()->items->first()->quantity)->toEqual(2);
});

it('remove the item from the cart when you are trying to remove more than or equal to the quantity in the cart ', function () {
    expect($this->projector)->toBeInstanceOf(CartProjector::class);

    $event = new DecreaseCartQuantity(
        cartID:         $cartId = Cart::factory()->create()->id,
        cartItemID:     CartItem::factory()->create(['cart_id'  =>  $cartId, 'quantity' => 1])->id,
        quantity:       1
    );

    $cart = Cart::query()->find($event->cartID);

    expect($cart->items->first()->quantity)->toEqual('1');

    $this->projector->onDecreaseCartQuantity(
        event: $event
    );

    $cart->refresh();

    expect($cart->items->count())->toEqual(0);
    expect(EloquentStoredEvent::query()->get())->toHaveCount(1);
    expect(EloquentStoredEvent::query()->orderBy('id', 'desc')->first()->event_class)->toEqual(ProductWasRemovedFromCart::class);
});

it('can apply coupon to the cart ', function () {
    $event = new CouponWasAppied(
        cartID: Cart::factory()->create()->id,
        code:   Coupon::factory()->create()->code,
    );

    expect(Cart::query()->find($event->cartID)->coupon)
    ->toBeNull();

    $this->projector->onCouponWasAppied(
        event: $event
    );
    expect(Cart::query()->find($event->cartID)->coupon)
    ->toBeString();

    expect(Cart::query()->find($event->cartID)->coupon)
    ->toEqual(Coupon::query()->where('code', $event->code)->first()->code);

    // expect(EloquentStoredEvent::query()->get())->toHaveCount(1);
    // expect(EloquentStoredEvent::query()->orderBy('id', 'desc')->first()->event_class)->toEqual(ProductWasRemovedFromCart::class);
});
