<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Carts\Coupons;

use Illuminate\Http\Response;
use Domains\Customer\Models\Cart;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Carts\Coupons\StoreRequest;
use Domains\Customer\Aggregates\CartAggregate;
use Domains\Customer\Models\Coupon;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request, Cart $cart)
    {
        $coupon = Coupon::query()->where('code', $request->get(key: 'code'))->firstOrFail();

        CartAggregate::retrieve(
            uuid: $cart->uuid
        )->applyCoupon(
            cartID: $cart->id,
            code:   $coupon->code
        )->persist();

        return new Response(
            content: null,
            status: Response::HTTP_ACCEPTED
        );
    }
}
