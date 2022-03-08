<?php

use Domains\Customer\Models\User;
use Domains\Customer\Models\Wishlist;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('can list all wishlists for a user', function () {
    auth()->login(User::factory()->create());

    Wishlist::factory()->create([
        'user_id' => auth()->id()
    ]);

    expect(auth()->user()->wishlists()->count())->toBe(1);

    get(
        uri: route('api:v1:wishlists:index')
    )->assertStatus(
        status: Response::HTTP_OK
    )->assertJson(
        value: fn (AssertableJson $json) => $json->count(1)
    );
});

it('can list all public wishlists', function () {
    Wishlist::factory(15)->create([
        'public' => true
    ]);

    Wishlist::factory(10)->create([
        'public' => false
    ]);

    get(
        uri: route('api:v1:wishlists:index')
    )->assertStatus(
        status: Response::HTTP_OK
    )->assertJson(
        value: fn (AssertableJson $json) => $json->count(15)
    );
});

it('can create a new wishlist', function () {
    auth()->login(User::factory()->create());

    expect(Wishlist::query()->count())->toBe(0);

    post(
        uri: route('api:v1:wishlists:store'),
        data: [
            'name'  => 'test',
        ]
    )->assertStatus(
        status: Response::HTTP_CREATED
    );
});

it('can show a specific wishlist', function () {
    $wishlist = Wishlist::factory()->create([
        'public'   =>  true
    ]);

    get(
        uri: route('api:v1:wishlists:show', $wishlist->uuid)
    )->assertStatus(
        status: Response::HTTP_OK
    )->assertJson(
        value: fn (AssertableJson $json) =>
        $json
        ->where('attributes.name', $wishlist->name)
        ->where('type', 'wishlist')
        ->where('id', $wishlist->uuid)
        ->etc()
    );
});
