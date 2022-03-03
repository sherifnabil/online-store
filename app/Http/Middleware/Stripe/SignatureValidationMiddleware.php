<?php

declare(strict_types=1);

namespace App\Http\Middleware\Stripe;

use Closure;
use Stripe\Webhook;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use UnexpectedValueException;
use Stripe\Exception\SignatureVerificationException;

class SignatureValidationMiddleware
{
    /**
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
    */
    public function handle(Request $request, Closure $next): mixed
    {
        try {
            Webhook::constructEvent(
                payload: $request->getContent(),
                sigHeader: $request->header(
                    key: 'Stripe-Signature'
                ),
                secret: config('services.stripe.endpoint_secret')
            );
        } catch (UnexpectedValueException $e) {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY); // Invalid payload
        } catch (SignatureVerificationException $e) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        $request->merge([
            'payload'   =>  $event
        ]);

        return $next($request);
    }
}
