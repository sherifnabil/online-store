<?php

declare(strict_types=1);

use Domains\Customer\Models\User;
use Domains\Customer\Models\CartItem;
use Domains\Customer\Models\Location;
use Domains\Fulfillment\Events\OrderWasCreated;
use Domains\Fulfillment\Aggregates\OrderAggregate;

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
            email:      'someemail@mail.com'
        )
    )->when(
        callable: function (OrderAggregate $aggregate) use ($item, $location) {
            $aggregate->createOrder(
                cart:       $item->cart->uuid,
                billing:    $location->id,
                shipping:   $location->id,
                user:       null,
                email:      'someemail@mail.com'
            );
        }
    )->assertRecorded(
        expectedEvents: new OrderWasCreated(
            cart:       $item->cart->uuid,
            billing:    $location->id,
            shipping:   $location->id,
            user:       null,
            email:      'someemail@mail.com'
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
            email:      null
        )
    )->when(
        callable: function (OrderAggregate $aggregate) use ($item, $location) {
            $aggregate->createOrder(
                cart:       $item->cart->uuid,
                billing:    $location->id,
                shipping:   $location->id,
                user:       auth()->id(),
                email:      null
            );
        }
    )->assertRecorded(
        expectedEvents: new OrderWasCreated(
            cart:       $item->cart->uuid,
            billing:    $location->id,
            shipping:   $location->id,
            user:       auth()->id(),
            email:      null
        )
    );
});
