<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Wishlists;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domains\Customer\Models\Wishlist;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\Api\V1\WishlistResource;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $wishlists = QueryBuilder::for(
            subject: Wishlist::class
        );

        if (auth()->check()) {
            $wishlists->whereHas(
                relation: 'owner',
                callback: fn (Builder $builder) => $builder->where('id', auth()->id())
            );
        } else {
            $wishlists->public();
        }

        return new JsonResponse(
            data: WishlistResource::collection(
                resource: $wishlists->get()
            ),
            status: Response::HTTP_OK
        );
    }
}
