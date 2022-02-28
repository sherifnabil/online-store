<?php

use Illuminate\Http\Response;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\patch;
use Domains\Customer\Models\Cart;

use function Pest\Laravel\delete;
use Domains\Catalog\Models\Variant;
use Domains\Customer\Models\CartItem;
use Illuminate\Testing\Fluent\AssertableJson;
use Domains\Customer\States\Statuses\CartStatus;
use Domains\Customer\Events\DecreaseCartQuantity;
use Domains\Customer\Events\IncreaseCartQuantity;
use Domains\Customer\Events\ProductWasRemovedFromCart;
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
});

it('can increase the quantity of an item in the cart', function () {
    expect(EloquentStoredEvent::query()->get())->toHaveCount(0);

    $item = CartItem::factory()->create(['quantity' =>  1]);

    expect($item->quantity)->toBe(1);

    patch(
        uri: route('api:v1:carts:products:update', [
            'cart'  =>  $item->cart->uuid,
            'item'  =>  $item->uuid,
        ]),
        data: ['quantity' => 3]
    )
    ->assertStatus(
        status: Response::HTTP_ACCEPTED
    );

    expect(EloquentStoredEvent::query()->get())->toHaveCount(1);
    expect(EloquentStoredEvent::query()->orderBy('id', 'desc')->first()->event_class)->toEqual(IncreaseCartQuantity::class);
});

it('can decrease the quantity of an item in the cart', function () {
    expect(EloquentStoredEvent::query()->get())->toHaveCount(0);

    $item = CartItem::factory()->create(['quantity' =>  3]);

    expect($item->quantity)->toBe(3);

    patch(
        uri: route('api:v1:carts:products:update', [
            'cart'  =>  $item->cart->uuid,
            'item'  =>  $item->uuid,
        ]),
        data: ['quantity' => 2]
    )
    ->assertStatus(
        status: Response::HTTP_ACCEPTED
    );

    expect(EloquentStoredEvent::query()->get())->toHaveCount(1);
    expect(EloquentStoredEvent::query()->orderBy('id', 'desc')->first()->event_class)->toEqual(DecreaseCartQuantity::class);
});

it('remove the item from the cart when quantity is zero', function () {
    $item = CartItem::factory()->create(['quantity' =>  3]);

    expect($item->quantity)->toBe(3);

    patch(
        uri: route('api:v1:carts:products:update', [
            'cart'  =>  $item->cart->uuid,
            'item'  =>  $item->uuid,
        ]),
        data: ['quantity' => 0]
    )
    ->assertStatus(
        status: Response::HTTP_ACCEPTED
    );

    expect(EloquentStoredEvent::query()->get())->toHaveCount(1);
    expect(EloquentStoredEvent::query()->orderBy('id', 'desc')->first()->event_class)->toEqual(ProductWasRemovedFromCart::class);
});

it('can remove an item from the cat', function () {
    $item = CartItem::factory()->create(['quantity' =>  3]);

    delete(
        uri: route(
            'api:v1:carts:products:delete',
            [
                'cart'  =>  $item->cart->uuid,
                'item'  =>  $item->uuid,
            ]
        )
    )->assertStatus(
        status: Response::HTTP_ACCEPTED
    );

    // assertDeleted($item);
    expect(EloquentStoredEvent::query()->orderBy('id', 'desc')->first()->event_class)->toEqual(ProductWasRemovedFromCart::class);
});
