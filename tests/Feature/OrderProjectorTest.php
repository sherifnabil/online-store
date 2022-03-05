<?php

declare(strict_types=1);

use Domains\Customer\Models\CartItem;
use Domains\Customer\Models\Location;
use Illuminate\Support\Arr;
use Domains\Fulfillment\Models\Order;
use Domains\Fulfillment\Projectors\OrderProjector;
use Domains\Fulfillment\Events\OrderStateWasUpdated;
use Domains\Fulfillment\Events\OrderWasCreated;

beforeEach(fn () => $this->projector = new OrderProjector());

it('can create new order', function () {
    expect($this->projector)->toBeInstanceOf(OrderProjector::class);
    $cartItem = CartItem::factory()->create();
    $location = Location::factory()->create();

    expect(Order::query()->count())->toEqual(0);

    $this->projector->onOrderWasCreated(
        event: new OrderWasCreated(
            cart: $cartItem->cart->uuid,
            billing: $location->id,
            shipping: $location->id,
            intent: 'test',
            email: 'test@test.com',
            user: null
        )
    );

    expect(Order::query()->count())->toEqual(1);
});

it('can update the status of an order', function () {
    expect($this->projector)->toBeInstanceOf(OrderProjector::class);

    $order = Order::factory()->create();
    $states = [
        'succeeded',
        'failed',
        'refunded',
    ];

    $this->projector->onOrderStatusWasUpdated(
        event: new OrderStateWasUpdated(
            id: $order->id,
            state: Arr::random($states),
        )
    );
    // dd($order);
    expect($order->refresh()->state)->toBeIn($states);
});
