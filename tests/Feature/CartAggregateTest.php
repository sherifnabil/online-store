<?php

declare(strict_types=1);

namespace Tests\Feature;

use Domains\Catalog\Models\Product;
use Domains\Catalog\Models\Variant;
use Domains\Customer\Aggregates\CartAggregate;
use Domains\Customer\Events\ProductWasAddedToCart;
use Domains\Customer\Models\Cart;

it('Can store an event for adding a product ', function () {
    $product = Variant::factory()->create();
    $cart = Cart::factory()->create();

    $event = new ProductWasAddedToCart(
        purchasableID: $product->id,
        cartID: $cart->id,
        type: Cart::class
    );
    CartAggregate::fake()
    ->given(
        events: [
            $event
        ]
    )
    ->when(
        callable: function (CartAggregate $cartAggregate) use ($product, $cart): void {
            $cartAggregate->addProduct(
                purchasableID: $product->id,
                cartID: $cart->id,
                type: Cart::class
            );
        }
    )
    ->assertRecorded(
        expectedEvents: new ProductWasAddedToCart(
            purchasableID: $product->id,
            cartID: $cart->id,
            type: Cart::class,
        )
    );
});
