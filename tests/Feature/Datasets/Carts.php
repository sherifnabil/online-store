<?php

use Domains\Customer\Models\Cart;

dataset('cart', [
    fn () => Cart::factory()->create()
]);
