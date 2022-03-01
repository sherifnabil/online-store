<?php

use Domains\Customer\Events\OrderWasCreated;
use Illuminate\Http\Response;
use function Pest\Laravel\post;
use Domains\Customer\Models\Order;

use Domains\Customer\Models\CartItem;
use Domains\Customer\Models\Location;
use Domains\Customer\Models\User;
use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;

it('can create an order from a cart using the API when not loggged in', function () {
    expect(Order::query()->count())->toEqual(0);
    expect(EloquentStoredEvent::query()->get())->toHaveCount(0);

    $item = CartItem::factory()->create();
    $location = Location::factory()->create();

    post(
        uri: route('api:v1:orders:store'),
        data: [
            'cart'  =>  $item->cart->uuid,
            'email' =>  'someemail@mail.com',
            'shipping'  =>  $location->id,
            'billing'  =>  $location->id,
        ]
    )->assertStatus(
        status: Response::HTTP_ACCEPTED
    );

    expect(Order::query()->count())->toEqual(1);
    expect(EloquentStoredEvent::query()->get())->toHaveCount(1);
    expect(EloquentStoredEvent::query()->first()->event_class)->toEqual(OrderWasCreated::class);
});

it('can create an order from a cart using the API when loggged in', function () {
    expect(Order::query()->count())->toEqual(0);
    expect(EloquentStoredEvent::query()->get())->toHaveCount(0);

    $item = CartItem::factory()->create();
    $location = Location::factory()->create();
    auth()->login(User::factory()->create());

    post(
        uri: route('api:v1:orders:store'),
        data: [
            'cart'      =>  $item->cart->uuid,
            'shipping'  =>  $location->id,
            'billing'   =>  $location->id,
        ]
    )->assertStatus(
        status: Response::HTTP_ACCEPTED
    );

    expect(Order::query()->count())->toEqual(1);
    expect(EloquentStoredEvent::query()->get())->toHaveCount(1);
    expect(EloquentStoredEvent::query()->first()->event_class)->toEqual(OrderWasCreated::class);
});
