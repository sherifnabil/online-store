<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Wishlists;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\WishlistResource;
use Domains\Customer\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ShowController extends Controller
{
    public function __invoke(Request $request, Wishlist $wishlist): JsonResponse
    {
        if (! $wishlist->public) {
            if (auth()->guest() || $wishlist->uswe_id !== $wishlist->user_id) {
                abort(Response::HTTP_FORBIDDEN);
            }
        }

        return new JsonResponse(
            data: new WishlistResource(
                resource: $wishlist
            ),
            status: Response::HTTP_OK
        );
    }
}
