<?php

declare(strict_types=1);

use Illuminate\Http\Response;

use function Pest\Laravel\delete;
use function Pest\Laravel\post;
use Domains\Customer\Models\Cart;
use Domains\Customer\Models\Coupon;

use Domains\Customer\Events\CouponWasAppied;
use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;

it('can apply coupon to the cart', function () {
    $cart = Cart::factory()->create();
    $coupon = Coupon::factory()->create();

    expect($cart)
    ->reduction->toEqual(0);

    post(
        uri: route('api:v1:carts:coupons:store', $cart->uuid),
        data: [
            'code'  =>  $coupon->code
        ]
    )->assertStatus(
        status: Response::HTTP_ACCEPTED
    );

    expect(
        Cart::query()->find($cart->id)
    )
    ->reduction->toEqual($coupon->reduction)
    ->coupon->toEqual($coupon->code);

    expect(EloquentStoredEvent::query()->orderBy('id', 'desc')->first()->event_class)->toEqual(CouponWasAppied::class);
});

it('can remove coupon from the cart', function (): void {
    $cart = Cart::factory() ->create();
    $coupon = Coupon::factory()->create();

    $cart->update([
        'coupon'    =>  $coupon->code,
        'reduction' =>  $coupon->reduction,
    ]);

    expect($cart->refresh())->coupon->toEqual($coupon->code);

    delete(
        uri: route(
            'api:v1:carts:coupons:delete',
            [
                'cart' => $cart->uuid,
                'uuid' => $coupon->uuid
            ]
        )
    )->assertStatus(
        status: Response::HTTP_ACCEPTED
    );

    expect($cart->refresh())->coupon->toBeNull();
});
