<?php

declare(strict_types=1);

namespace Domains\Fulfillment\Jobs\Stripe;

use Domains\Fulfillment\ValueObjects\Stripe\PaymentIntent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPaymentIntent implements ShouldQueue
{
    use Queueable;
    use Dispatchable;
    use SerializesModels;
    use InteractsWithQueue;

    public function __construct(
        public PaymentIntent $intent
    ) {
    }


    public function handle(): void
    {
        //
    }
}
