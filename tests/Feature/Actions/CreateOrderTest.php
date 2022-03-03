<?php

use Domains\Customer\Models\User;
use Domains\Customer\Models\CartItem;
use Domains\Customer\Models\Location;
use Domains\Fulfillment\Models\Order;
use Domains\Fulfillment\Models\OrderLine;
use Domains\Fulfillment\Actions\CreateOrder;
use Domains\Fulfillment\ValueObjects\OrderValueObject;

it('can create an order', function () {
    $object = new OrderValueObject(
        cart: CartItem::factory()->create()->cart->uuid,
        billing: $locationId = Location::factory()->create()->id,
        shipping: $locationId,
        user: User::factory()->create()->id,
        email: null,
        intent: null
    );

    expect(Order::query()->count())->toEqual(0);
    // dd($object);

    CreateOrder::handle(
        object: $object
    );

    expect(Order::query()->count())->toEqual(1);
    expect(OrderLine::query()->count())->toEqual(1);
});
