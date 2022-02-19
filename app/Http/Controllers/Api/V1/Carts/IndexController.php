<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Carts;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CartResource;

class IndexController extends Controller
{
    public function __invoke(Request $request): Response|JsonResponse
    {
        if (! auth()->check() || ! auth()->user()->cart()->count()) {
            return new Response(
                content: null,
                status: Response::HTTP_NO_CONTENT
            );
        }

        return new JsonResponse(
            data: new CartResource(
                resource: auth()->user()->cart
            ),
            status: Response::HTTP_OK
        );
    }
}
