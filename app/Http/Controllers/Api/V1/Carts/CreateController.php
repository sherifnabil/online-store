<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Carts;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domains\Customer\Actions\CreateCart;
use App\Http\Resources\Api\V1\CartResource;
use Domains\Customer\Factories\CartFactory;
use Domains\Customer\States\Statuses\CartStatus;

class CreateController extends Controller
{
    /**
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $cart = CreateCart::handle(
            cart: CartFactory::make(
                attributes:[
                    'status'    =>  CartStatus::pending()->value,
                    'user_id'   =>  auth()->id() ?? null
                ]
            )
        );

        return new JsonResponse(
            data: new CartResource(
                resource: $cart
            ),
            status: Response::HTTP_CREATED
        );
    }
}
