<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Wishlists;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domains\Customer\Actions\CreateWishlist;
use App\Http\Resources\Api\V1\WishlistResource;

use Domains\Customer\Factories\WishlistFactory;
use App\Http\Requests\Api\V1\Wishlists\StoreRequest;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request): JsonResponse
    {
        $wishlist = CreateWishlist::handle(
            object: WishlistFactory::make(
                attributes: [
                    'name'  =>  $request->get('name'),
                    'public'=>  $request->get('public', false),
                    'user'  =>  auth()->id() ?? null
                ]
            ),
        );
        return new JsonResponse(
            data: new WishlistResource(
                resource: $wishlist
            ),
            status: Response::HTTP_CREATED
        );
    }
}
