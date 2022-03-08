<?php

declare(strict_types=1);

use Domains\Customer\Models\User;
use Domains\Customer\Models\CartItem;
use Domains\Customer\Models\Location;
use Domains\Fulfillment\Models\Order;
use Domains\Fulfillment\Events\OrderWasCreated;
use Domains\Fulfillment\Aggregates\OrderAggregate;
use Domains\Fulfillment\States\Statuses\OrderStatus;
use Domains\Fulfillment\Events\OrderStateWasUpdated;

it('can create an order for an unauthenticated user', function () {
    $item = CartItem::factory()->create(['quantity'   =>  3]);
    $location = Location::factory()->create();

    OrderAggregate::fake()
    ->given(
        events: new OrderWasCreated(
            cart:       $item->cart->uuid,
            billing:    $location->id,
            shipping:   $location->id,
            user:       null,
            email:      'someemail@mail.com',
            intent:     '1234',
        )
    )->when(
        callable: function (OrderAggregate $aggregate) use ($item, $location) {
            $aggregate->createOrder(
                cart:       $item->cart->uuid,
                billing:    $location->id,
                shipping:   $location->id,
                user:       null,
                email:      'someemail@mail.com',
                intent:     '1234',
            );
        }
    )->assertRecorded(
        expectedEvents: new OrderWasCreated(
            cart:       $item->cart->uuid,
            billing:    $location->id,
            shipping:   $location->id,
            user:       null,
            email:      'someemail@mail.com',
            intent:     '1234',
        )
    );
});

it('can create an order for an authenticated user', function () {
    $item = CartItem::factory()->create(['quantity'   =>  3]);
    $location = Location::factory()->create();

    auth()->login(User::factory()->create());

    OrderAggregate::fake()
    ->given(
        events: new OrderWasCreated(
            cart:       $item->cart->uuid,
            billing:    $location->id,
            shipping:   $location->id,
            user:       auth()->id(),
            email:      null,
            intent:     '1234',
        )
    )->when(
        callable: function (OrderAggregate $aggregate) use ($item, $location) {
            $aggregate->createOrder(
                cart:       $item->cart->uuid,
                billing:    $location->id,
                shipping:   $location->id,
                user:       auth()->id(),
                email:      null,
                intent:     '1234',
            );
        }
    )->assertRecorded(
        expectedEvents: new OrderWasCreated(
            cart:       $item->cart->uuid,
            billing:    $location->id,
            shipping:   $location->id,
            user:       auth()->id(),
            email:      null,
            intent:     '1234',
        )
    );
});

it('can update an orders status', function () {
    auth()->login(User::factory()->create());

    $order = Order::factory()->create();

    OrderAggregate::fake(
        uuid: $order->uuid
    )->given(
        events: new OrderStateWasUpdated(
            id: $order->id,
            state: OrderStatus::completed()->value,
        )
    )->when(
        callable: function (OrderAggregate $aggregate) use ($order) {
            $aggregate->updateState(
                id: $order->id,
                state: OrderStatus::completed()->value,
            );
        }
    )->assertRecorded(
        expectedEvents: new OrderStateWasUpdated(
            id: $order->id,
            state: OrderStatus::completed()->value,
        ),
    );
});
