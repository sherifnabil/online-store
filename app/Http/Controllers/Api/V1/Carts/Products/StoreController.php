<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Carts\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Carts\ProductRequest;
use Domains\Customer\Aggregates\CartAggregate;
use Domains\Customer\Models\Cart;
use Illuminate\Http\Response;

class StoreController extends Controller
{
    public function __invoke(ProductRequest $request, Cart $cart): Response
    {
        CartAggregate::retrieve(
            uuid: $cart->uuid
        )->addProduct(
            purchasableID: $request->get('purchasable_id'),
            cartID: $cart->id,
            type: $request->get('purchasable_type')
        )->persist();

        return new Response(
            content:null,
            status: Response::HTTP_CREATED
        );
    }
}
