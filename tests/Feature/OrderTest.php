<?php

use Domains\Customer\Events\OrderWasCreated;
use Illuminate\Http\Response;
use function Pest\Laravel\post;
use Domains\Customer\Models\User;
use Domains\Customer\Models\Order;
use Domains\Customer\Models\CartItem;

use Domains\Customer\Models\Location;
use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;

it('can create an order from a cart for an unauthenticated user', function () {
    $cartItem = CartItem::factory(['quantity'   =>  3])->create();

    $location = Location::factory()->create();
    expect(Order::query()->count())->toEqual(0);
    expect(EloquentStoredEvent::query()->get())->toHaveCount(0);

    post(
        uri: route('api:v1:orders:store'),
        data: [
            'cart'      =>  $cartItem->cart->uuid,
            'email'     =>  'email@email.com',
            'shipping'  =>  $location->id,
            'billing'   =>  $location->id,
        ]
    )
    ->assertStatus(
        status: Response::HTTP_CREATED
    );

    expect(EloquentStoredEvent::query()->get())->toHaveCount(1);
    expect(EloquentStoredEvent::query()->first()->event_class)->toEqual(OrderWasCreated::class);
});

it('can create an order from a cart for an authenticated user', function () {
    auth()->login(User::factory()->create());
    $cartItem = CartItem::factory(['quantity'   =>  3])->create();

    $location = Location::factory()->create();

    expect(Order::query()->count())->toEqual(0);
    expect(EloquentStoredEvent::query()->get())->toHaveCount(0);

    post(
        uri: route('api:v1:orders:store'),
        data: [
            'cart'      =>  $cartItem->cart->uuid,
            'shipping'  =>  $location->id,
            'billing'   =>  $location->id,
        ]
    )
    ->assertStatus(
        status: Response::HTTP_CREATED
    );

    expect(EloquentStoredEvent::query()->get())->toHaveCount(1);
    expect(EloquentStoredEvent::query()->first()->event_class)->toEqual(OrderWasCreated::class);
});
