<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Carts\Products;

use Domains\Customer\Models\Cart;
use App\Http\Controllers\Controller;
use Domains\Customer\Models\CartItem;
use App\Http\Requests\Api\V1\Carts\Products\UpdateRequest;
use Domains\Customer\Actions\ChangeCartQuantity;
use Domains\Customer\Aggregates\CartAggregate;
use Illuminate\Http\Response;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Cart $cart, CartItem $item): Response
    {
        ChangeCartQuantity::handle(
            cart: $cart,
            item: $item,
            quantity: $request->get('quantity'),
        );

        return new Response(
            content: null,
            status: Response::HTTP_ACCEPTED
        );
    }
}
