<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Orders;

use App\Http\Controllers\Controller;
use App\Jobs\Stripe\ProcessPaymentIntent;
use Domains\Fulfillment\Factories\Stripe\PaymentIntentFactory;
use Illuminate\Http\Request;

class StripeWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->get('type') === 'payment_intent.succeeded') {
            ProcessPaymentIntent::dispatch(
                PaymentIntentFactory::make(
                    event: $request->get('payload')
                )
            );
        }
    }
}
