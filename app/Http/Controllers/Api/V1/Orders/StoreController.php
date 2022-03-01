<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Orders;

use Illuminate\Support\Str;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Orders\StoreRequest;
use Domains\Fulfillment\Aggregates\OrderAggregate;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request): Response
    {
        OrderAggregate::retrieve(
            uuid: Str::uuid()->toString()
        )->createOrder(
            cart:       $request->get('cart'),
            shipping:   $request->get('shipping'),
            billing:    $request->get('billing'),
            email:      auth()->guest() ? $request->get('email') : null,
            user:       auth()->check() ? auth()->id() : null,
        )->persist();

        if (auth()->check()) {
            //  send a notification
        }

        return new Response(
            content: null,
            status: Response::HTTP_ACCEPTED
        );
    }
}
