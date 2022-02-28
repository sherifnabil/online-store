<?php

use Domains\Catalog\Models\Variant;
use Domains\Customer\Events\IncreaseCartQuantity;
use Domains\Customer\Events\ProductWasAddedToCart;
use Domains\Customer\Events\ProductWasRemovedFromCart;
use Domains\Customer\Models\Cart;
use Domains\Customer\Models\CartItem;
use Domains\Customer\Projectors\CartProjector;

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

    expect($cart->items->first()->quantity)
    ->toEqual(1);

    // $this->projector->onIncreaseCartQuantity(
    //     event: $event
    // );
});
