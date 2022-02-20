<?php

use function Pest\Laravel\get;
use function Pest\Laravel\post;

use Domains\Catalog\Models\Variant;
use Illuminate\Http\Response;
use Domains\Customer\Models\Cart;
use Illuminate\Testing\Fluent\AssertableJson;
use Domains\Customer\States\Statuses\CartStatus;
use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;

it('creates a cart for an unauthenticated user', function () {
    post(
        uri: route('api:v1:carts:store')
    )
    ->assertStatus(
        status: Response::HTTP_CREATED
    )
    ->assertJson(
        fn (AssertableJson $json) =>
        $json
            ->where('type', 'cart')
            ->where('attributes.status', CartStatus::pending()->label)
            ->etc()
    );
});

it('returns a cart for a loggedin user', function () {
    $cart = Cart::factory()->create();

    auth()->loginUsingId($cart->user_id);

    get(
        uri: route('api:v1:carts:index')
    )
    ->assertStatus(
        status: Response::HTTP_OK
    );
});

it('return a no content when a guest tries to retrive their carts', function () {
    get(
        uri: route('api:v1:carts:index')
    )
    ->assertStatus(
        status: Response::HTTP_NO_CONTENT
    );
});

it('can add a new product to a cart', function () {
    // expect(EloquentStoredEvent::query()->get())->toHaveCount(0);

    $cart = Cart::factory()->create();
    $variant = Variant::factory()->create();

    post(
        uri: route('api:v1:carts:products:store', $cart->uuid),
        data: [
            'quantity'  =>  1,
            'purchasable_id'    =>  $variant->id,
            'purchasable_type'  =>  'variant',
        ]
    )
    ->assertStatus(
        status: Response::HTTP_CREATED
    );

    expect(EloquentStoredEvent::get())->toHaveCount(1);
});
