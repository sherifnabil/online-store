<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Orders;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Domains\Fulfillment\Jobs\Stripe\ProcessPaymentIntent;
use Domains\Fulfillment\Factories\Stripe\PaymentIntentFactory;
use Domains\Fulfillment\Models\Order;

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
