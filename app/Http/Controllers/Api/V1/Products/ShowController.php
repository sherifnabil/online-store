<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Products;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Domains\Catalog\Models\Product;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\Api\V1\ProductResource;
use Illuminate\Http\JsonResponse;

class ShowController extends Controller
{
    public function __invoke(Request $request, string $key): JsonResponse
    {
        $product = QueryBuilder::for(
            subject: Product::class,
        )->allowedIncludes(
            includes: ['variants', 'category', 'range']
        )
        ->where('key', $key)
        ->firstOrFail();

        return response()->json(
            data: new ProductResource(
                resource: $product
            ),
            status: Response::HTTP_OK
        );
    }
}
