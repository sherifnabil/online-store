<?php

use Domains\Customer\States\Statuses\CartStatus;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\post;

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
